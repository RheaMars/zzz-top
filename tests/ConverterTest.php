<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use src\php\Converter;

/**
 * @covers \src\php\Converter
 */
final class ConverterTest extends TestCase
{
    /**
     * @dataProvider provideArabicConversionData
     */
    public function testGetArabicConversion(array $lexicographicVersion, array $arabicVersion): void
    {
        $converter = new Converter();

        $this->assertSame($arabicVersion, $converter->getArabicConversion($lexicographicVersion));
    }

    public function provideArabicConversionData(): array
    {
        return [
            [
                [0],
                [0],
            ],
            [
                [-20],
                [-20],
            ],
            [
                [12],
                [12],
            ],
            [
                ["c"],
                [3],
            ],
            [
                [0, "a", "zb"],
                [0, 1, 28],
            ],
            [
                [36, "d", "f", "zz", "za", "z", "zg"],
                [36, 4, 6, 52, 27, 26, 33],
            ],
            [
                [356, "zzz"],
                [356, 78]
            ]
        ];
    }

    /**
     * @dataProvider provideGreekConversionData
     */
    public function testGetGreekConversion(array $arabicVersion, array $greekVersion): void
    {
        $converter = new Converter();

        $this->assertSame($greekVersion, $converter->getGreekConversion($arabicVersion));
    }

    public function provideGreekConversionData(): array
    {
        return [
            [
                [0],
                [0],
            ],
            [
                [-20],
                [-20],
            ],
            [
                [12],
                [12],
            ],
            [
                [1, 3],
                [1, "γ"],
            ],
            [
                [0, 1, 28],
                [0, "α", "κη"],
            ],
            [
                [36, 4, 6, 52, 27, 26, 33],
                [36, "δ", "στ", "νβ", "κζ", "κστ", "λγ"],
            ],
            [
                [356, 78],
                [356, "οη"]
            ],
            [
                [1, pow(10, Converter::GREEK_MAX_EXPONENT) - 1],
                [1, "͵ϡ͵ϟ͵θϡϟθ"]
            ]
        ];
    }

    public function testGetGreekConversionThrowsException(): void
    {
        $converter = new Converter();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('not supported (segment 1000000 is higher than 999999).');
        $converter->getGreekConversion([1, pow(10, Converter::GREEK_MAX_EXPONENT)]);
    }
}
