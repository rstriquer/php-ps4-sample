<?php
/**
 * App QuestionService.
 * 
 * @category   Services
 * @package    App\Services
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Services;

use App\Repositories\KnowledgeRepository;
use App\Session;
use App\Models\KnowledgeModel;
use Exception;

class QuestionService
{
    private KnowledgeRepository $repository;
    private KnowledgeModel $model;
    /**
     * First item to find ih the database
     * - Answare to the /start API method
     * @var string
     */
    private const FIRST_UUID = '51b1e62d-cfcc-45b8-93cc-8f196c3b6f12';

    public function __construct()
    {
        $this->initModelRepository();

        if (Session::get('lastUUID') !== null)
        {
            $this->model = $this->model->find(Session::get('lastUUID'));
        }
    }
    private function init(): void
    {
        $this->model = $this->model->find(self::FIRST_UUID);
        Session::set('lastUUID', $this->model->getUUID());
        Session::set('gameOn', true);
        Session::set('giveUp', false);
    }
    private function rebuildKnowledge(): void
    {
        if (is_file(getenv('DB_DATABASE')))
        {
            unlink(getenv('DB_DATABASE'));
        }
        $this->initModelRepository();
    }
    private function initModelRepository() : void
    {
        $this->model = new KnowledgeModel();
        $this->repository = new KnowledgeRepository($this->model);
    }
    public function initGame(): string
    {
        Session::clear();
        $this->rebuildKnowledge();
        $this->init();
        return 'Think of a meal you like and then go to \'/start\'?';
    }
    public function startRound(): string
    {
        $this->init();
        return 'The meal you thoguht of is \'' . $this->model->getItem() . '\'? Go to \'/yes\' if so, \'/no\' otherwise.';
    }
    /**
     * Get the text to the next level
     * - check if we should use the yes link or the no link to find the next
     * chain link, move to that link and return question with that information
     *
     * @param boolean $yes Tells if we should use the yes link or the no link
     * @author Ricado Soares <ricardo.soares@rentcars.com>
     */
    private function getNextLevel(bool $yes = false): string
    {
        $message = 'Is it \'';
        if ($yes) {
            $record = $this->model->find($this->model->getYes());
        } else {
            $record = $this->model->find($this->model->getNo());
        }
        Session::set('lastUUID', $record->getUUID());
        $message .= $record->getItem()
            . '\' the meal you thoguht of? If not go to \'/no\' otherwise play \'/yes\' again.';
        return $message;
    }
    private function noActiveRound(): bool
    {
        return !Session::get('gameOn');
    }
    private function noMoreOptions(bool $yes = true): bool
    {
        if ($yes) {
            $method = 'getYes';
        } else {
            $method = 'getNo';
        }
        return $this->model->{$method}() == null;
    }
    public function yes() : string
    {
        if ($this->noActiveRound()) {
            throw new Exception('Plase, go to \'/start\' to start another round');
        }

        if ($this->noMoreOptions()) {
            Session::set('gameOn', false);
            $message = 'Shotgun! Do you wanna keep on playing? If so, go to \'/start\'';
        } else {
            $message = $this->getNextLevel(true);
        }
        return $message;
    }
    public function no() : string
    {
        if ($this->noActiveRound()) {
            throw new Exception('Plase, go to \'/start\' to start another round');
        }

        if ($this->noMoreOptions(false)) {
            Session::set('giveUp', true);
            $message = 'Ok, I give up! Go to \'/add\' and tell me your toght or go to \'/start\' for another round!';
        } else {
            $message = $this->getNextLevel();
        }
        return $message;
    }

    /**
     * Add a record on the database
     * - it must have a 'element' with the name or description of the element
     * and a 'definition' definig the item been added.
     *
     * @param [type] $link
     * @author Ricado Soares <ricardo.soares@rentcars.com>
     */
    public function addLink($link) : string
    {
        if ($this->noActiveRound()) {
            throw new Exception('Plase, go to \'/start\' to start another round');
        }

        if (!Session::get('giveUp')) {
            throw new Exception('I have not give it up yet! Answare \'/yes\' or \'/no\' to the last question! Or do YOU give up? If so, go to \'/start\' for another round.');
        }

        if (
            $link == null
            || !is_array($link)
            || !isset($link['element'])
            || !isset($link['definition'])
        ) {
            throw new Exception('Plase, you must provide a \'element\' and a \'definition\' values!');
        }

        $item = $this->repository->findByItem($link['element']);
        $definition = $this->repository->findByItem($link['definition']);
        if (
            $item !== null
            && $definition !== null
            && $item->getItem()->getYes() == $definition->getUUID()
        ) {
            $message = 'I already know that! Please, provide another one.';

        } else if ($definition) {
            $definition = $this->model->find($item->getItem()->getYes());
            $message = 'I already know \'' . $link['definition']
                . '\' but it is \'' . $definition->getItem()
                . '\' not \'' . $link['element'] . '\'!';

        } else {
            $this->model->create($link);
            $message = 'Thanks to teatch me. Now lets play again? Go to \'/start\' for another round!';
        }
        return $message;
    }
    public function showKnowledge(): array
    {
        $data = [];
        $knowledge = $this->model->all();
        $i=0;
        foreach($knowledge AS $item) {
            $data[$i++] = $item;
        }
        return $data;
    }
}
