<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

class ParserCoreTimeAndDistanceTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider timeAndDistanceProvider
     */
    public function testParsesTimeAndDistanceCases($input, $expected)
    {
        $parser = new TestableParserCore($input);
        $parsed = $parser->parseToArray();

        $this->assertSame($expected, $parsed['exercises'][0]['data'][0]);
    }

    public function testParsesDistanceWithTrailingComment()
    {
        $parser = new TestableParserCore("#Run\n5km x1 endurance\n");
        $parsed = $parser->parseToArray();

        $this->assertSame(
            [
                'D' => ['5', 'km'],
                'R' => '1',
                'C' => 'endurance',
            ],
            $parsed['exercises'][0]['data'][0]
        );
    }

    public function timeAndDistanceProvider()
    {
        return [
            'seconds unit becomes time in seconds' => [
                "#Run\n90sec x1\n",
                [
                    'T' => ['90', 's'],
                    'R' => '1',
                ],
            ],
            'minutes alias becomes time in minutes' => [
                "#Run\n5mins x1\n",
                [
                    'T' => ['5', 'm'],
                    'R' => '1',
                ],
            ],
            'hour alias becomes time in hours' => [
                "#Run\n1hour x1\n",
                [
                    'T' => ['1', 'h'],
                    'R' => '1',
                ],
            ],
            'meters become distance' => [
                "#Run\n500m x2\n",
                [
                    'D' => ['500', 'm'],
                    'R' => '2',
                ],
            ],
            'mile unit is normalized as distance' => [
                "#Run\n5mile x1\n",
                [
                    'D' => ['5', 'mile'],
                    'R' => '1',
                ],
            ],
            'bare m unit is treated as distance not time' => [
                "#Run\n5m x1\n",
                [
                    'D' => ['5', 'm'],
                    'R' => '1',
                ],
            ],
            'plain clock time keeps empty unit' => [
                "#Plank\n1:30\n",
                [
                    'T' => ['1:30', ''],
                ],
            ],
        ];
    }
}
