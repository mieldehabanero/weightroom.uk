<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

class ParserCoreLegacyMultilineQuirkTest extends PHPUnit\Framework\TestCase
{
    public function testParsesRepeatedSetLinesWithImplicitCopiedWeight()
    {
        $parser = new TestableParserCore("#Bench\n100kg x5, x5, x5\n");
        $parsed = $parser->parseToArray();

        $this->assertCount(2, $parsed['exercises'][0]['data']);
        $this->assertSame(
            [
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
            $parsed['exercises'][0]['data'][0]
        );
        $this->assertSame(
            [
                'C' => '',
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
            $parsed['exercises'][0]['data'][1]
        );
    }

    public function testDropsTrailingCommentFromImplicitRepeatedSetLine()
    {
        $parser = new TestableParserCore("#Bench\n100kg x5, x5 note\n");
        $parsed = $parser->parseToArray();

        $this->assertCount(2, $parsed['exercises'][0]['data']);
        $this->assertSame(
            [
                'C' => '',
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
            $parsed['exercises'][0]['data'][1]
        );
    }

    /**
     * @dataProvider legacyCommaRepeatProvider
     */
    public function testParsesLegacyCommaRepeatEdgeCases($input, $expectedFirstSet, $expectedSecondSet)
    {
        $parser = new TestableParserCore($input);
        $parsed = $parser->parseToArray();

        $this->assertSame($expectedFirstSet, $parsed['exercises'][0]['data'][0]);
        $this->assertSame($expectedSecondSet, $parsed['exercises'][0]['data'][1]);
    }

    public function legacyCommaRepeatProvider()
    {
        return [
            'explicit same repeated set is misparsed into reps plus comment' => [
                "#Bench\n100kg x5,100kg x5\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => '5',
                ],
                [
                    'R' => '100',
                    'W' => ['100', 'kg'],
                    'C' => 'kg x5',
                ],
            ],
            'explicit new weight after comma still keeps first weight and spills into comment' => [
                "#Bench\n100kg x5,105kg x5\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => '5',
                ],
                [
                    'R' => '105',
                    'W' => ['100', 'kg'],
                    'C' => 'kg x5',
                ],
            ],
        ];
    }
}
