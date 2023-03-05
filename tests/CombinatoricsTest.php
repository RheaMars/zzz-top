<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use src\php\CombinationsCalculator;

/**
 * @covers \src\php\CombinationsCalculator
 */
final class CombinatoricsTest extends TestCase
{
    /**
     * @dataProvider provideSetAndSubsetSizeData
     */
    public function testCombinations(array $set, int $subsetSize, $expectedCombinations): void
    {
        $combinationsCalculator = new CombinationsCalculator();
        $this->assertSame($expectedCombinations, $combinationsCalculator->combinations($set, $subsetSize));
    }

    public function provideSetAndSubsetSizeData(): array
    {
        return [
            [
                [
                    'one'   => 'a',
                    'two'   => 'b',
                    'three' => 'c',
                ],
                3,
                [
                    ['one' => 'a', 'two' => 'b', 'three' => 'c'],
                ],
            ],
            [
                [
                    'one'   => 'a',
                    'two'   => 'b',
                    'three' => 'c',
                ],
                2,
                [
                    ['one' => 'a', 'two' => 'b'],
                    ['one' => 'a', 'three' => 'c'],
                    ['two' => 'b', 'three' => 'c'],
                ],
            ],
            [
                ['a', 'b'],
                999,
                [
                    [0 => 'a', 1 => 'b']
                ],
            ],
            [
                ['a', 'b'],
                0,
                [],
            ],
            [
                ['a', 'b'],
                -10,
                [],
            ],
            [
                [0, 1, 2],
                1,
                [
                    [0 => 0],
                    [0 => 1],
                    [0 => 2]
                ],
            ],
            [
                [0, 1, 2],
                2,
                [
                    [0 => 0, 1 => 1],
                    [0 => 0, 2 => 2],
                    [1 => 1, 2 => 2]
                ],
            ],
            [
                [0, 1, 2],
                3,
                [
                    [0 => 0, 1 => 1, 2 => 2]
                ],
            ],
            [
                [0, 1, 2, 3],
                2,
                [
                    [0 => 0, 1 => 1],
                    [0 => 0, 2 => 2],
                    [0 => 0, 3 => 3],
                    [1 => 1, 2 => 2],
                    [1 => 1, 3 => 3],
                    [2 => 2, 3 => 3],
                ],
            ],
        ];
    }
}
