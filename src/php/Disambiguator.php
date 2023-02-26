<?php
declare(strict_types = 1);

namespace src\php;

final class Disambiguator
{
    private const AMBIGUOUS_LETTER = "z";

    public function disambiguate(string $ambiguousString): array
    {
        $articleNumber = $this->getArticleNumber($ambiguousString);
        $paragraphsAsString = $this->getParagraphString($ambiguousString);

        $paragraphSegments = $this->getSegmentsFromParagraphString($paragraphsAsString);
        $ambiguousSegments = $this->getAmbiguousSegments($paragraphSegments);
        $combinationsOfAmbiguousSegments = $this->getCombinationsOfAmbiguousSegments($ambiguousSegments);
        
        $paragraphSegmentAlternatives = $this->getParagraphSegmentAlternatives($paragraphSegments, $combinationsOfAmbiguousSegments);

        return $this->getLexicographicAlternatives($articleNumber, $paragraphSegmentAlternatives);
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
     * Extract the article number the ambiguous string starts with.
     * E.g. for the input "1234zabcc" the article number would be 1234.
     */
    private function getArticleNumber(string $ambiguousString): int
    {
        $filteredNumbers = array_filter(preg_split("/\D+/", $ambiguousString));
        $articleNumber = reset($filteredNumbers);

        return (int)$articleNumber;
    }

    /**
     * Extract the string of paragraphs from the ambiguous string.
     * E.g. for the input "123zabcc" the string of paragraphs would be "zabcc".
     */
    private function getParagraphString(string $ambiguousString): string
    {
        $articleNumber = $this->getArticleNumber($ambiguousString);
        return substr($ambiguousString, strlen((string)$articleNumber));
    }

    /**
     * Identifies all indentation alternatives for all given ambiguous segments.
     * Note that the outmost keys of the result array are the keys we also have in the segments array.
     * E.g., if the $ambiguousSegments is given as the associative array [2 => "za", 5 => "zzb"]
     * then the function returns the associative array
     * [2 => [["za"], ["z", "a"]], 5 => [["zzb"], ["zz", "b"], ["z", "zb"], ["z", "z", "b"]]]
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
     * For example, if $paragraphSegments is the array ["a", "za"] and
     * $combinationsOfAmbiguousSegments the array [1 => [["za"], ["z", "a"]]]
     * then the function returns
     * [[["a"]], [["za"], ["z", "a"]]]
     * Note that the indices of the second input correspond to the implicit indices of the first.
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

    private function getLexicographicAlternatives(int $articleNumber, array $paragraphSegmentAlternatives): array
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
}