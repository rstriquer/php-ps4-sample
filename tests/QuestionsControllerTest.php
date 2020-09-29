<?php

use App\Controllers\QuestionsController;
use App\Services\QuestionService;

class QuestionsControllerTest extends PHPUnit\Framework\TestCase
{
    public function testRenderHomeReturnsStart()
    {
        $service = new QuestionService();
        $test = new QuestionsController($service);
        $expected = 'Hello Word';
        $this->assertEquals($expected, $test->startRound());
    }
}