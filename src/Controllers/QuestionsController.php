<?php
/**
 * App QuestionsController.
 * 
 * @category   Controller
 * @package    App\Controller
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Controllers;

use App\Services\QuestionService;
use App\Http;
use Exception;

class QuestionsController extends Controller
{
    use \App\Http\JsonResponseTrait;

    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        parent::__construct();
        $this->questionService = $questionService;
    }

    public function initGame(): void
    {
        try {
            $this->json($this->questionService->initGame());
        } catch (\Exception | \RuntimeException $e) {
            if (! $e instanceof \Exception) {
                if (getenv('APP_DEBUG')) {
                    $e = new Exception($e->getMessage(), $e->getCode(), $e);
                } else {
                    $e = new Exception('Undefined error', $e->getCode(), $e);
                }
            }
            $this->jsonError($e);
        }
    }
    public function startRound() : void
    {
        try {
            $this->json($this->questionService->startRound());
        } catch (\Exception $e) {
            $this->jsonError($e);
        }
    }
    public function getYes() : bool
    {
        try {
            $this->json($this->questionService->yes());
        } catch (\Exception $e) {
            $this->jsonError($e);
        }
    }

    public function getNo() : bool
    {
        try {
            $this->json($this->questionService->no());
        } catch (\Exception $e) {
            $this->jsonError($e);
        }
    }
    public function addLink() : bool
    {
        try {
            $this->json($this->questionService->addLink($this->requestBody));
        } catch (\Exception | \RuntimeException $e) {
            if (! $e instanceof \Exception) {
                $e = new Exception($e->getMessage(), $e->getCode(), $e);
            }
            $this->jsonError($e);
        }
    }
    public function showKnowledge(): bool
    {
        try {
            $this->json($this->questionService->showKnowledge($this->requestBody));
        } catch (\Exception $e) {
            $this->jsonError($e);
        }
    }
}
