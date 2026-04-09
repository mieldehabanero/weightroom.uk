<?php

require_once __DIR__ . '/Support/TestableParserCore.php';

use App\Extend\ParserCore;

class ParserCoreInternalHelpersTest extends PHPUnit\Framework\TestCase
{
    private function invokeParserCoreMethod($parser, $method, array $arguments = [])
    {
        $reflection = new ReflectionMethod(ParserCore::class, $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($parser, $arguments);
    }

    public function testFormatCheckReturnsTrueForCommentBlockAndFalseForInvalidFormat()
    {
        $parser = new TestableParserCore("#Bench\n100kg x5\n");

        $currentBlocksProperty = new ReflectionProperty(ParserCore::class, 'current_blocks');
        $currentBlocksProperty->setAccessible(true);
        $currentBlocksProperty->setValue($parser, ['C']);

        $this->assertTrue($this->invokeParserCoreMethod($parser, 'format_check'));

        $currentBlocksProperty->setValue($parser, ['W']);

        $possibleFormatsProperty = new ReflectionProperty(ParserCore::class, 'possible_formats');
        $possibleFormatsProperty->setAccessible(true);
        $possibleFormatsProperty->setValue($parser, ['W' => ['0kg']]);

        $formatDumpProperty = new ReflectionProperty(ParserCore::class, 'format_dump');
        $formatDumpProperty->setAccessible(true);
        $formatDumpProperty->setValue($parser, 'definitely-not-a-valid-format');

        $this->assertFalse($this->invokeParserCoreMethod($parser, 'format_check'));
    }

    public function testCleanMultilineDataMovesCommentAndAccumulatesSetCountForRepeatedRows()
    {
        $parser = new TestableParserCore("#Bench\n100kg x5\n");
        $outputData = [
            0 => [
                'W' => ['100', 'kg'],
                'R' => '5',
                'C' => 'tail comment',
            ],
            1 => [
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
            2 => [
                'W' => ['100', 'kg'],
                'R' => '5',
            ],
        ];

        $this->invokeParserCoreMethod($parser, 'cleanMultilineData', [&$outputData, 2]);

        $this->assertSame(
            [
                0 => [
                    'W' => ['100', 'kg'],
                    'R' => '5',
                    'S' => 3,
                    'C' => 'tail comment',
                ],
            ],
            $outputData
        );
    }

    public function testFlagErrorEchoesTheProvidedMessage()
    {
        $parser = new TestableParserCore("#Bench\n100kg x5\n");

        ob_start();
        $this->invokeParserCoreMethod($parser, 'flag_error', ['legacy parser error']);
        $output = ob_get_clean();

        $this->assertSame('legacy parser error', $output);
    }
}
