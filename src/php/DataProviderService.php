<?php
declare(strict_types = 1);

namespace src\php;

use Exception;

final class DataProviderService
{
    public function getData(string $originalInput): array
    {
        $disambiguator = new Disambiguator();
        $converter = new Converter();

        $lexicographicAlternatives = $disambiguator->disambiguate($originalInput); 

        $output = [];

        foreach ($lexicographicAlternatives as $lexicographicAlternative) {

            $arabicAlternative = $converter->getArabicConversion($lexicographicAlternative);

            try {
                $greekAlternative = $converter->getGreekConversion($arabicAlternative);
            }
            catch (Exception $e) {
                $greekAlternative = [$e->getMessage()];
            }

            $output[] = [
                "lexicographic" => implode(".", $lexicographicAlternative),
                "arabic" => implode(".", $arabicAlternative),
                "greek" => implode(".", $greekAlternative)
            ];
        }

        return $output;
    }

    /*
     * Checks if the original input is a valid string.
     * A valid string must start with a positive number and must be followed by and number of lowercase chars between a and z.
     * Valid examples: 12, 12a, 12xyzza
     * Invalid examples: -12, ax, 12-abc, 12abc3
     */
    public function isInputValid(string $originalInput): bool
    {
        return preg_match('/^[1-9]\d*[a-z]*$/', $originalInput) === 1;
    }
}