<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

class ParserCoreWeightAndBodyweightTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider weightAndBodyweightProvider
     */
    public function testParsesWeightAndBodyweightCases($input, $expected)
    {
        $parser = new TestableParserCore($input);
        $parsed = $parser->parseToArray();

        $this->assertSame($expected, $parsed['exercises'][0]['data'][0]);
    }

    public function weightAndBodyweightProvider()
    {
        return [
            'weight with separated unit' => [
                "#Squat\n100 kg x5\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => '5',
                ],
            ],
            'decimal weight is preserved' => [
                "#Bench\n100.5kg x3\n",
                [
                    'W' => ['100.5', 'kg'],
                    'R' => '3',
                ],
            ],
            'inline trailing text becomes set comment' => [
                "#Bench\n100kg x5 note\n",
                [
                    'W' => ['100', 'kg'],
                    'R' => '5',
                    'C' => 'note',
                ],
            ],
            'bodyweight without offset' => [
                "#Pullup\nBW x5\n",
                [
                    'W' => ['bw', ''],
                    'R' => '5',
                ],
            ],
            'bodyweight minus offset' => [
                "#Pullup\nBW-10 x5\n",
                [
                    'W' => ['bw-10', ''],
                    'R' => '5',
                ],
            ],
            'bodyweight plus decimal offset is preserved' => [
                "#Squat\nBW+2.5 x5\n",
                [
                    'W' => ['bw+2.5', ''],
                    'R' => '5',
                ],
            ],
        ];
    }
}
