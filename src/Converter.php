<?php
declare(strict_types = 1);

namespace src;

use Exception;

class Converter
{
    private string $originalInput;

    private const AMBIGUOUS_LETTER = "z";

    private const GREEK_MAX_NUMBER = 999999;

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

    public function __construct(string $originalInput) {

        $this->originalInput = $originalInput;

    }

    public function computeOutput(): array
    {
        $articleNumber = $this->getArticleNumber();
        $paragraphsAsString = $this->getParagraphString();
        $paragraphSegments = $this->getSegmentsFromParagraphString($paragraphsAsString);
        $ambiguousSegments = $this->getAmbiguousSegments($paragraphSegments);
        $combinationsOfAmbiguousSegments = $this->getCombinationsOfAmbiguousSegments($ambiguousSegments);
        $paragraphSegmentAlternatives = $this->getParagraphSegmentAlternatives($paragraphSegments, $combinationsOfAmbiguousSegments);

        $indentationAlternatives = $this->getIndentationAlternatives($articleNumber, $paragraphSegmentAlternatives);

        return $this->prepareOutputData($indentationAlternatives);
    }

    /*
     * Checks if the input is a valid string.
     * A valid string must start with a positive number and must be followed by and number of lowercase chars between a and z.
     * Valid examples: 12, 12a, 12xyzza
     * Invalid examples: -12, ax, 12-abc, 12abc3
     */
    public function isInputValid(string $input): bool
    {
        return preg_match('/^[1-9]\d*[a-z]*$/', $input) === 1;
    }

    /**
     * Extract the single paragraph segments from a paragraph string.
     * E.g. for the paragraph string "abzczzzka" the segments would be ["a", "b", "zc", "zzzk", "a"].
     * We bundle all paragraph segments containing a "z" to be able to find all possible combinations of
     * these ambiguous segments later.
     */
    private function getSegmentsFromParagraphString(string $paragraphsAsString): array
    {
        preg_match_all("/[a-y]|z+[a-y]|z+/", $paragraphsAsString, $matches);
        return $matches[0];
    }

    /**
     * Extract all ambiguous segments from an array.
     * Note: a "z" in the end of the input array is never ambiguous.
     * E.g. for the paragraph segments ["a", "b", "ze", "z"] we retrieve the following ambiguous segments:
     * ["ze"].
     */
    private function getAmbiguousSegments(array $paragraphSegments): array
    {
        $ambiguousSegments = [];
        foreach ($paragraphSegments as $key => $segment) {

            // exclude last entry of ambiguous letter:
            if (self::AMBIGUOUS_LETTER === $segment && $key === (sizeof($paragraphSegments) - 1)) {
                continue;
            }

            if (str_contains($segment, self::AMBIGUOUS_LETTER)) {
                $ambiguousSegments[$key] = str_split($segment);
            }
        }

        return $ambiguousSegments;
    }

    /**
     * Extract the article number the input strings starts with.
     * E.g. for the input "1234zabcc" the article number would be 1234.
     */
    private function getArticleNumber(): int
    {
        $filteredNumbers = array_filter(preg_split("/\D+/", $this->originalInput));
        $articleNumber = reset($filteredNumbers);

        return (int)$articleNumber;
    }

    /**
     * Extract the string of paragraphs from the original input.
     * E.g. for the input "123zabcc" the string of paragraphs would be "zabcc".
     */
    private function getParagraphString(): string
    {
        $articleNumber = $this->getArticleNumber(false);
        return substr($this->originalInput, strlen((string)$articleNumber));
    }

    /**
     * Identifies all indentation alternatives for all given ambiguous segments.
     * Note that the outmost keys of the result array are the keys we also have in the segments array.
     */
    private function getCombinationsOfAmbiguousSegments(array $ambiguousSegments): array
    {
        $combinationsOfAmbiguousSegments = [];

        foreach ($ambiguousSegments as $key => $ambiguousSegment) {

            $ambiguousSegmentString = implode($ambiguousSegment);

            $alternativeSegments = [];

            $splitPositionsForSegment = $this->getSplitPositionsForSegment($ambiguousSegment);

            foreach ($splitPositionsForSegment as $splitPositions) {
                $alternativeSegment = $this->getAlternativeSegmentBySplitPositions($ambiguousSegmentString, $splitPositions);
                $alternativeSegments[] = $alternativeSegment;
            }

            $combinationsOfAmbiguousSegments[$key] = $alternativeSegments;
        }

        return $combinationsOfAmbiguousSegments;
    }

    /**
     * By a given segment string and its split positions create an array of its possible indentation alternatives.
     * E.g. for segment "zzc" with split positions [0] we will retrieve ["z", "zc"],
     * with split positions [0, 1] we wil retrieve ["z", "z", "c"].
     */
    private function getAlternativeSegmentBySplitPositions(string $ambiguousSegmentString, array $splitPositions): array
    {
        $resultString = $ambiguousSegmentString;

        $increasePositionCounter = strlen(self::AMBIGUOUS_LETTER);

        foreach ($splitPositions as $splitPosition) {
            $resultString = substr_replace($resultString, ".", $splitPosition + $increasePositionCounter, 0);
            $increasePositionCounter += $increasePositionCounter;
        }

        return explode(".", trim($resultString, "."));
    }

    /**
     * Identifies all possible indentations (or splits) for the given segment as combinations of split positions.
     * E.g. for segment "zzc" we have the split positions 0, 1 and (0 and 1) which will lead to "z.zc", "zz.c" and "z.z.c".
     */
    private function getSplitPositionsForSegment(array $ambiguousSegment): array
    {
        $combinatorics = new Combinatorics();

        $ambiguousCharacterHeadLength = count($ambiguousSegment) - 1; // last character is never ambiguous so we subtract 1
        $keyIndexRange = range(0, $ambiguousCharacterHeadLength - 1);

        $splitPositionsForSegment = [
            [sizeof($ambiguousSegment) - 1] // the ambiguous segment itself is always a valid alternative, so we add it here
        ];

        for ($i = 0; $i <= sizeof($keyIndexRange); $i++) {
            $splitPositions = $combinatorics->combinations($keyIndexRange, $i);

            foreach ($splitPositions as $splitPosition) {
                $splitPositionsForSegment[] = $splitPosition;
            }
        }

        return $splitPositionsForSegment;
    }

    /**
     * Gathers alternatives of unambiguous and ambiguous segments in a single array.
     */
    private function getParagraphSegmentAlternatives(array $paragraphSegments, array $combinationsOfAmbiguousSegments): array
    {
        $paragraphSegmentAlternatives = [];

        foreach ($paragraphSegments as $key => $paragraphSegment) {
            if (array_key_exists($key, $combinationsOfAmbiguousSegments)) {
                $paragraphSegmentAlternatives[$key] = $combinationsOfAmbiguousSegments[$key];
            }
            else {
                $paragraphSegmentAlternatives[$key] = [[$paragraphSegment]];
            }
        }

        return $paragraphSegmentAlternatives;
    }

    private function getIndentationAlternatives(int $articleNumber, array $paragraphSegmentAlternatives): array
    {
        $alternatives = [[$articleNumber]];
        foreach ($paragraphSegmentAlternatives as $alternative) {
            $alternatives = $this->getCartesianProduct($alternatives, $alternative);
        }

        return $alternatives;

    }

    /**
     * Returns the cartesian product of two arrays.
     * E.g. for $array1 with [["a"], ["b"]] and $array2 with [["zk"], ["z", "k"]]
     * the function returns an array of [["a", "zk"], ["a", "z", "k"], ["b", "zk"], ["b", "z", "k"]]
     */
    private function getCartesianProduct(array $array1, array $array2): array
    {
        $cartesianArray = [];
        foreach ($array1 as $array1Entry) {
            foreach($array2 as $array2Entry) {
                $cartesianArray[] = array_merge($array1Entry, $array2Entry);
            }
        }
        return $cartesianArray;
    }

    private function prepareOutputData(array $alternatives): array
    {
        $output = [];

        foreach ($alternatives as $alternative) {

            $arabicAlternative = $this->getArabicConversion($alternative);

            try {
                $greekAlternative = $this->getGreekConversion($arabicAlternative);
            }
            catch (Exception $e) {
                $greekAlternative = [$e->getMessage()];
            }


            $output[] = [
                "lexicographic" => implode(".", $alternative),
                "arabic" => implode(".", $arabicAlternative),
                "greek" => implode(".", $greekAlternative)
            ];
        }

        return $output;
    }

    private function getArabicConversion(array $alternative): array
    {
        $range = range("a", "z");

        $arabicAlternative = [];

        foreach ($alternative as $alternativeSegment) {

            // article number segment
            if (is_numeric($alternativeSegment)) {
                $arabicAlternative[] = $alternativeSegment;
            }
            // single or multiple characters segment
            else {
                $sum = 0;
                $chars = str_split($alternativeSegment);
                foreach ($chars as $char) {
                    $sum += array_keys($range, $char)[0] + 1;
                }
                $arabicAlternative[] = $sum;
            }
        }
        return $arabicAlternative;
    }

    private function getGreekConversion(array $arabicAlternative): array
    {
        $greekAlternative = [];

        $segmentCounter = 0;
        foreach ($arabicAlternative as $arabicAlternativeSegment) {
            $segmentCounter++;

            $greekAlternativeSegment = '';

            if ($segmentCounter === 1) {
                $greekAlternative[] = $arabicAlternativeSegment;
                continue;
            }

            if ($segmentCounter > 1 && $arabicAlternativeSegment > self::GREEK_MAX_NUMBER) {
                throw new Exception("not supported (segment $arabicAlternativeSegment is higher than " . self::GREEK_MAX_NUMBER . ").");
            }

            $numbersToMap = [];
            
            $currentRemainder = $arabicAlternativeSegment;
            for ($exponent = 5; $exponent >= 0; $exponent--) {
                $quotient = (int)($currentRemainder / pow(10, $exponent));
                $remainder = $currentRemainder % pow(10, $exponent);

                $numbersToMap[] = $quotient * pow(10, $exponent);
                $currentRemainder = $remainder;
            }

            $greekAlternativeSegment = '';
            foreach ($numbersToMap as $numberToMap) {
                if (0 === $numberToMap) {
                    continue;
                }
                $greekAlternativeSegment .= self::GREEK_MAPPING[$numberToMap];
            }
            
            $greekAlternative[] = $greekAlternativeSegment;
                        
        }
        return $greekAlternative;
    }
}