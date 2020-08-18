<?php


namespace Cms\Controllers;

use Exception;
use Cms\Services\QuestionService;
use Cms\Http;

class QuestionsController extends Controller
{
    use \Cms\Http\JsonResponseTrait;

    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        parent::__construct();
        $this->questionService = $questionService;
    }

    public function initGame(): void
    {
        $this->json($this->questionService->initGame());
    }
    public function startRound() : void
    {
        $this->json($this->questionService->startRound());
    }
    public function getYes() : bool {
        $this->json($this->questionService->yes());
    }

    public function getNo() : bool {
        $this->json($this->questionService->no());
    }
    public function addLink() : bool {
        $this->json($this->questionService->addLink($this->requestBody));
    }
}
