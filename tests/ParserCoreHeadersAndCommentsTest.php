<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

class ParserCoreHeadersAndCommentsTest extends PHPUnit\Framework\TestCase
{
    public function testParsesTopLevelCommentExerciseGroupsAndSetFields()
    {
        $parser = new TestableParserCore("General note\n#Squat #legs #comp\n100kg x5\nBW+10 x3 @8 hard\n");
        $parsed = $parser->parseToArray();

        $this->assertSame('General note', $parsed['comment']);
        $this->assertSame('Squat', $parsed['exercises'][0]['name']);
        $this->assertSame([1 => 'legs', 2 => 'comp'], $parsed['exercises'][0]['groups']);
        $this->assertSame(
            [
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
            $parsed['exercises'][0]['data'][0]
        );
        $this->assertSame(
            [
                'W' => ['bw+10', ''],
                'R' => '3',
                'P' => '8',
                'C' => 'hard',
            ],
            $parsed['exercises'][0]['data'][1]
        );
    }

    public function testParsesExerciseGroupsWithoutSpacesBetweenTags()
    {
        $parser = new TestableParserCore("#Squat#legs#comp\n100kg x5\n");
        $parsed = $parser->parseToArray();

        $this->assertSame('Squat', $parsed['exercises'][0]['name']);
        $this->assertSame([1 => 'legs', 2 => 'comp'], $parsed['exercises'][0]['groups']);
    }

    public function testIgnoresBlankLinesInTopLevelComment()
    {
        $parser = new TestableParserCore("note a\n\n#Squat\n100kg x5\n");
        $parsed = $parser->parseToArray();

        $this->assertSame('note a', $parsed['comment']);
        $this->assertSame('Squat', $parsed['exercises'][0]['name']);
    }

    public function testKeepsNumericTextBeforeFirstExerciseInTopLevelComment()
    {
        $parser = new TestableParserCore("123abc\n#Bench\n100kg x5\n");
        $parsed = $parser->parseToArray();

        $this->assertSame('123abc', $parsed['comment']);
        $this->assertSame('Bench', $parsed['exercises'][0]['name']);
    }

    public function testAppendsNonDataExerciseLinesToExerciseComment()
    {
        $parser = new TestableParserCore("#Deadlift\nNeeds straps\n100kg x3\n");
        $parsed = $parser->parseToArray();

        $this->assertSame("Needs straps\n \n", $parsed['exercises'][0]['comment']);
        $this->assertSame(
            [
                'W' => ['100', 'kg'],
                'R' => '3',
            ],
            $parsed['exercises'][0]['data'][0]
        );
    }
}
