<?php
declare(strict_types = 1);

namespace src\php;

/**
 * Adapted from https://pear.php.net/package/Math_Combinatorics.
 */
final class Combinatorics
{
    private array $pointers = [];

    /**
     * Find all combinations given a set and a subset size.
     */
    public function combinations(array $set, int $subsetSize): array
    {
        $setSize = count($set);

        if ($subsetSize >= $setSize) {
            return [$set];
        } else if ($subsetSize == 1) {
            return array_chunk($set, 1);
        } else if ($subsetSize <= 0) {
            return [];
        }

        $combinations = [];
        $setKeys = array_keys($set);
        $this->pointers = array_slice(array_keys($setKeys), 0, $subsetSize);

        $combinations[] = $this->getCombination($set);
        while ($this->advancePointers($subsetSize - 1, $setSize - 1)) {
            $combinations[] = $this->getCombination($set);
        }

        return $combinations;
    }

    /**
     * Recursive function used to advance the list of 'pointers' that record the
     * current combination.
     *
     * @return bool True if a pointer was advanced, false otherwise
     */
    private function advancePointers(int $pointerNumber, int $pointerLimit): bool
    {
        if ($pointerNumber < 0) {
            return false;
        }

        if ($this->pointers[$pointerNumber] < $pointerLimit) {
            $this->pointers[$pointerNumber]++;
            return true;
        } else {
            if ($this->advancePointers($pointerNumber - 1, $pointerLimit - 1)) {
                $this->pointers[$pointerNumber] = $this->pointers[$pointerNumber - 1] + 1;
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Get the current combination.
     */
    private function getCombination(array $parentSet): array
    {
        $setKeys = array_keys($parentSet);

        $combination = [];

        foreach ($this->pointers as $pointer) {
            $combination[$setKeys[$pointer]] = $parentSet[$setKeys[$pointer]];
        }

        return $combination;
    }
}

