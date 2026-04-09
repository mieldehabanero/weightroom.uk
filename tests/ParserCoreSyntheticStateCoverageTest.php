<?php

class ParserCoreSyntheticStateCoverageTest extends PHPUnit\Framework\TestCase
{
    public function testDocumentsRemainingTailMergeBranchesAsSyntheticOnly()
    {
        $this->markTestIncomplete(
            'Remaining uncovered ParserCore::parseLine() branches are the tail-merge paths at lines 210, 212, 213, and 217. ' .
            'They appear to require mid-method synthetic state where output_data[$multiline][current_block] already exists ' .
            'before the final chunk append step. No public text input has been found that reaches them cleanly.'
        );
    }
}
