<?php
declare(strict_types = 1);

namespace src\php;

use Exception;

final class Converter
{
    public function getArabicConversion(array $lexicographicAlternative): array
    {
        $range = range("a", "z");

        $arabicAlternative = [];

        foreach ($lexicographicAlternative as $lexicographicAlternativeSegment) {

            // article number segment
            if (is_numeric($lexicographicAlternativeSegment)) {
                $arabicAlternative[] = $lexicographicAlternativeSegment;
            }
            // single or multiple characters segment
            else {
                $sum = 0;
                $chars = str_split($lexicographicAlternativeSegment);
                foreach ($chars as $char) {
                    $sum += array_keys($range, $char)[0] + 1;
                }
                $arabicAlternative[] = $sum;
            }
        }
        return $arabicAlternative;
    }

    public function getGreekConversion(array $arabicAlternative): array
    {
        $greekAlternative = [];

        $headSegment = $arabicAlternative[0];
        $tailSegments = array_slice($arabicAlternative, 1);

        $greekAlternative[] = $headSegment;
        foreach ($tailSegments as $tailSegment) {

            $greekMaxNumber = pow(10, Mapping::GREEK_MAX_EXPONENT) - 1;
            if ($tailSegment > $greekMaxNumber) {
                throw new Exception("not supported (segment $tailSegment is higher than $greekMaxNumber).");
            }

            $greekAlternativeSegmentNumbers = $this->getPowerOfTenSummands($tailSegment, Mapping::GREEK_MAX_EXPONENT);

            $greekAlternativeSegment = $this->mapIntegersByConversionArray($greekAlternativeSegmentNumbers, Mapping::GREEK_NO_STIGMA);
            
            $greekAlternative[] = $greekAlternativeSegment;
                        
        }
        return $greekAlternative;
    }

    /**
     * Returns the decimal representation summands of an integer as an array of integers.
     * E.g., for the $number 154, the function returns [100, 50, 4].
     */
    private function getPowerOfTenSummands(int $number, int $maxExponent): array
    {
        $numbersToMap = [];
            
        $currentRemainder = $number;
        for ($exponent = ($maxExponent - 1); $exponent >= 0; $exponent--) {
            $quotient = (int)($currentRemainder / pow(10, $exponent));
            $remainder = $currentRemainder % pow(10, $exponent);

            $numbersToMap[] = $quotient * pow(10, $exponent);
            $currentRemainder = $remainder;
        }
        return $numbersToMap;
    }

    /**
     * Converts each integer of an array based on an associative array that specifies the conversion.
     * E.g., if $numbers is [1, 2, 3] and the $conversionArray is [1=>'hallo', 2=>'world', 3=>'!...'],
     * then the function returns ['hallo', 'world', '!...'].
     */
    private function mapIntegersByConversionArray(array $numbers, array $conversionArray): string
    {
        $greekAlternativeSegment = '';
        foreach ($numbers as $number) {
            if (0 === $number) {
                continue;
            }
            $greekAlternativeSegment .= $conversionArray[$number];
        }

        return $greekAlternativeSegment;
    }
}