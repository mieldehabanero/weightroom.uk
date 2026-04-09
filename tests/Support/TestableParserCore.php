<?php

use App\Extend\ParserCore;

class TestableParserCore extends ParserCore
{
    public function parseToArray()
    {
        $this->parseText();

        return $this->log_data;
    }
}
