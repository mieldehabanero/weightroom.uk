<?php

class ExampleTest extends TestCase
{
    public function testBasicExample()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Track and analyse your training.');
    }
}
