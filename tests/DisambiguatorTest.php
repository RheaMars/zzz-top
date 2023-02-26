<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use src\php\Disambiguator;

/**
 * @covers \src\php\Disambiguator
 */
final class DisambiguatorTest extends TestCase
{
    /**
     * @dataProvider provideAmbiguousStringData
     */
    public function testDisambiguate(string $ambiguousString, array $lexicographicAlternatives): void
    {
        $disambiguator = new Disambiguator();
        $this->assertSame($lexicographicAlternatives, $disambiguator->disambiguate($ambiguousString));
    }

    public function provideAmbiguousStringData(): array
    {
        return [
            [
                '',
                [[0]],
            ],
            [
                '0',
                [[0]],
            ],
            [
                '12',
                [[12]],
            ],
            [
                '12a',
                [[12, 'a']],
            ],
            [
                '12aa',
                [[12, 'a', 'a']],
            ],
            [
                '12za',
                [[12, 'za'], [12, 'z', 'a']],
            ],
            [
                '123zza',
                [[123, 'zza'], [123, 'z', 'za'], [123, 'zz', 'a'], [123, 'z', 'z', 'a']],
            ],
            [
                '123abz',
                [[123, 'a', 'b', 'z']],
            ],
        ];
    }
}
