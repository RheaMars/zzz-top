<?php
declare(strict_types = 1);

namespace src\php;

use Exception;

final class Converter
{
    public const GREEK_MAX_EXPONENT = 6;

    private const GREEK_MAPPING = [
        1 => 'α',
        2 => 'β',
        3 => 'γ',
        4 => 'δ',
        5 => 'ε',
        // 6 => 'ϛ',
        6 => 'στ',
        7 => 'ζ',
        8 => 'η',
        9 => 'θ',
        10 => 'ι',
        20 => 'κ',
        30 => 'λ',
        40 => 'μ',
        50 => 'ν',
        60 => 'ξ',
        70 => 'ο',
        80 => 'π',
        // 90 => 'πι',
        90 => 'ϟ',
        100 => 'ρ',
        200 => 'σ',
        300 => 'τ',
        400 => 'υ',
        500 => 'φ',
        600 => 'χ',
        700 => 'ψ',
        800 => 'ω',
        900 => 'ϡ',
        1000 => '͵α',
        2000 => '͵β',
        3000 => '͵γ',
        4000 => '͵δ',
        5000 => '͵ε',
        // 6000 => '͵ϛ',
        6000 => '͵στ',
        7000 => '͵ζ',
        8000 => '͵η',
        9000 => '͵θ',
        10000 => '͵ι',
        20000 => '͵κ',
        30000 => '͵λ',
        40000 => '͵μ',
        50000 => '͵ν',
        60000 => '͵ξ',
        70000 => '͵ο',
        80000 => '͵π',
        // 90000 => '͵πι',
        90000 => '͵ϟ',
        100000 => '͵ρ',
        200000 => '͵σ',
        300000 => '͵τ',
        400000 => '͵υ',
        500000 => '͵φ',
        600000 => '͵χ',
        700000 => '͵ψ',
        800000 => '͵ω',
        900000 => '͵ϡ',
    ];

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

            $greekMaxNumber = pow(10, self::GREEK_MAX_EXPONENT) - 1;
            if ($tailSegment > $greekMaxNumber) {
                throw new Exception("not supported (segment $tailSegment is higher than $greekMaxNumber).");
            }

            $greekAlternativeSegmentNumbers = $this->getPowerOfTenSummands($tailSegment);

            $greekAlternativeSegment = $this->mapIntegersByConversionArray($greekAlternativeSegmentNumbers, self::GREEK_MAPPING);
            
            $greekAlternative[] = $greekAlternativeSegment;
                        
        }
        return $greekAlternative;
    }

    /**
     * Returns the decimal representation summands of an integer as an array of integers.
     * E.g., for the $number 154, the function returns [100, 50, 4].
     */
    private function getPowerOfTenSummands(int $number): array
    {
        $numbersToMap = [];
            
        $currentRemainder = $number;
        for ($exponent = (self::GREEK_MAX_EXPONENT - 1); $exponent >= 0; $exponent--) {
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