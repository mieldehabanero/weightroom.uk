<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

class ParserCoreLegacyFormattingQuirkTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider formattingQuirkProvider
     */
    public function testParsesFormattingQuirks($input, $expected)
    {
        $parser = new TestableParserCore($input);
        $parsed = $parser->parseToArray();

        $this->assertSame($expected, $parsed['exercises'][0]['data'][0]);
    }

    public function formattingQuirkProvider()
    {
        return [
            'unicode multiply keeps leading space in reps' => [
                "#Bench\n100kg × 5\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => ' 5',
                ],
            ],
            'asterisk multiply keeps leading space in reps' => [
                "#Bench\n100kg * 5\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => ' 5',
                ],
            ],
            'time comment with current speed truncation bug' => [
                "#Plank\n1:30 speed\n",
                [
                    'T' => ['1:30', 's'],
                    'C' => 'peed',
                ],
            ],
            'dangling rep digits merge into existing rep string' => [
                "#Bench\n100kg x5 5\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => '5 5',
                ],
            ],
        ];
    }
}
