<?php
declare(strict_types = 1);

namespace src\php;

use Exception;

final class DataProvider
{
    public function getData(string $ambiguousString, bool $setShowOptionsLimit = true): array
    {
        $disambiguator = new Disambiguator();
        $converter = new Converter();

        $lexicographicAlternatives = $disambiguator->disambiguate($ambiguousString, $setShowOptionsLimit);

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
                'lexicographic' => implode('.', $lexicographicAlternative),
                'arabic' => implode('.', $arabicAlternative),
                'greek' => implode('.', $greekAlternative)
            ];
        }

        return $output;
    }

    /*
     * Checks if the given input is a valid string.
     * A valid string must start with a positive number and must be followed by and number of lowercase chars between a and z.
     * Valid examples: 12, 12a, 12xyzza
     * Invalid examples: -12, ax, 12-abc, 12abc3
     */
    public function isInputValid(string $ambiguousString): bool
    {
        return preg_match('/^[1-9]\d*[a-z]*$/', $ambiguousString) === 1;
    }

    public function getNumberOfAlternatives(string $ambiguousString): int
    {
        $numberOfAmbiguousCharacters = substr_count(substr($ambiguousString, 0, strlen($ambiguousString) - 1), Disambiguator::AMBIGUOUS_LETTER);
        return pow(2, $numberOfAmbiguousCharacters);
    }
}