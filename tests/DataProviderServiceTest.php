<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use src\php\DataProviderService;

/**
 * @covers \src\php\DataProviderService
 */
final class DataProviderServiceTest extends TestCase
{
    /**
     * @dataProvider provideAmbiguousStringData
     */
    public function testIsInputValid(string $ambiguousString, bool $expectedResult): void
    {
        $service = new DataProviderService();

        if (true === $expectedResult) {
            $this->assertTrue($service->isInputValid($ambiguousString));
        }
        else {
            $this->assertFalse($service->isInputValid($ambiguousString));
        }

    }

    public function provideAmbiguousStringData(): array
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
}
