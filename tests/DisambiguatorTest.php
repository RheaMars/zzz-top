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
    public function testDisambiguate(string $ambiguousString, array $lexicographicAlternatives, bool $setShowOptionsLimit): void
    {
        $disambiguator = new Disambiguator();
        $this->assertSame($lexicographicAlternatives, $disambiguator->disambiguate($ambiguousString, $setShowOptionsLimit));
    }

    public function provideAmbiguousStringData(): array
    {
        return [
            [
                '',
                [[0]],
                false
            ],
            [
                '',
                [[0]],
                true
            ],
            [
                '0',
                [[0]],
                false
            ],
            [
                '0',
                [[0]],
                true
            ],
            [
                '12',
                [[12]],
                false
            ],
            [
                '12',
                [[12]],
                true
            ],
            [
                '12a',
                [[12, 'a']],
                false
            ],
            [
                '12a',
                [[12, 'a']],
                true
            ],
            [
                '12aa',
                [[12, 'a', 'a']],
                false
            ],
            [
                '12aa',
                [[12, 'a', 'a']],
                true
            ],
            [
                '12za',
                [[12, 'za'], [12, 'z', 'a']],
                false
            ],
            [
                '12za',
                [[12, 'za'], [12, 'z', 'a']],
                true
            ],
            [
                '123zza',
                [[123, 'zza'], [123, 'zz', 'a'], [123, 'z', 'za'], [123, 'z', 'z', 'a']],
                false
            ],
            [
                '123zza',
                [[123, 'zza'], [123, 'zz', 'a']],
                true
            ],
            [
                '123abz',
                [[123, 'a', 'b', 'z']],
                false
            ],
            [
                '123abz',
                [[123, 'a', 'b', 'z']],
                true
            ],
        ];
    }
}
