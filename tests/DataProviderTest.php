<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use src\php\DataProvider;

/**
 * @covers \src\php\DataProvider
 */
final class DataProviderTest extends TestCase
{
    /**
     * @dataProvider provideValidAmbiguousStringData
     */
    public function testGetData(string $ambiguousString, array $expectedData): void
    {
        $dataProvider = new DataProvider();

        $this->assertSame($expectedData, $dataProvider->getData($ambiguousString));
    }

    public function provideValidAmbiguousStringData(): array
    {
        return [
            [
                '12az',
                [
                    [
                        'lexicographic' => '12.a.z',
                        'arabic' => '12.1.26',
                        'greek' => '12.α.κστ'
                    ]
                ],
            ],
            [
                '12',
                [
                    [
                    'lexicographic' => '12',
                    'arabic' => '12',
                    'greek' => '12'
                    ]
                ],
            ],
            [
                '333azb',
                [
                    [
                        'lexicographic' => '333.a.zb',
                        'arabic' => '333.1.28',
                        'greek' => '333.α.κη'
                    ],
                    [
                        'lexicographic' => '333.a.z.b',
                        'arabic' => '333.1.26.2',
                        'greek' => '333.α.κστ.β'
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideValidAndInvalidAmbiguousStringData
     */
    public function testIsInputValid(string $ambiguousString, bool $expectedResult): void
    {
        $service = new DataProvider();

        if (true === $expectedResult) {
            $this->assertTrue($service->isInputValid($ambiguousString));
        }
        else {
            $this->assertFalse($service->isInputValid($ambiguousString));
        }
    }

    public function provideValidAndInvalidAmbiguousStringData(): array
    {
        return [
            [
                '',
                false,
            ],
            [
                '0',
                false,
            ],
            [
                '-12',
                false,
            ],
            [
                'ab',
                false,
            ],
            [
                '12-abc',
                false,
            ],
            [
                '12abc3',
                false,
            ],
            [
                '12A',
                false,
            ],
            [
                '12',
                true,
            ],
            [
                '12a',
                true,
            ],
            [
                '5555523za',
                true
            ],
        ];
    }

    public function testGetNumberOfAlternatives(): void
    {
        $dataProvider = new DataProvider();
        $this->assertSame(1, $dataProvider->getNumberOfAlternatives('12az'));
        $this->assertSame(8, $dataProvider->getNumberOfAlternatives('12azzzz'));
        $this->assertSame(1024, $dataProvider->getNumberOfAlternatives('12azbzzdzzzezzfffzzgg'));
    }
}
