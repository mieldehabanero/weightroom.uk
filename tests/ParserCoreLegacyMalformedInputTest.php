<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

class ParserCoreLegacyMalformedInputTest extends PHPUnit\Framework\TestCase
{
    public function testDropsDanglingIntensityMarkerAtEndOfLine()
    {
        $parser = new TestableParserCore("#Bench\n100kg x5 @\n");
        $parsed = $parser->parseToArray();

        $this->assertSame(
            [
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
            $parsed['exercises'][0]['data'][0]
        );
    }

    public function testParsesUnknownNumericPrefixAsUnknownBlockPlusComment()
    {
        $parser = new TestableParserCore("#Bench\n123abc\n");
        $parsed = $parser->parseToArray();

        $this->assertSame(
            [
                'U' => '123',
                'C' => 'abc',
            ],
            $parsed['exercises'][0]['data'][0]
        );
    }

    public function testTreatsBodyweightGarbageLineAsExerciseComment()
    {
        $parser = new TestableParserCore("#Bench\nBlargh\n");
        $parsed = $parser->parseToArray();

        $this->assertSame("Blargh\n \n", $parsed['exercises'][0]['comment']);
        $this->assertSame([], $parsed['exercises'][0]['data']);
    }

    public function testTreatsMalformedBodyweightOffsetLineAsExerciseComment()
    {
        $parser = new TestableParserCore("#Bench\nBW+ x5\n");
        $parsed = $parser->parseToArray();

        $this->assertSame("BW+ x5\n \n", $parsed['exercises'][0]['comment']);
        $this->assertSame([], $parsed['exercises'][0]['data']);
    }

    public function testTreatsDanglingBodyweightPrefixAsExerciseComment()
    {
        $parser = new TestableParserCore("#Bench\nBW+\n");
        $parsed = $parser->parseToArray();

        $this->assertSame("BW+\n \n", $parsed['exercises'][0]['comment']);
        $this->assertSame([], $parsed['exercises'][0]['data']);
    }
}
