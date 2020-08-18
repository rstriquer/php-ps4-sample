<?php


namespace Cms\Services;

use Cms\Session;

class QuestionService
{
    private array $node;
    private array $knowledge;

    public function __construct()
    {
        $this->node = Session::getArray('node');
        $this->knowledge = Session::getArray('knowledge');
    }
    private function init(): void
    {
        Session::set('last', key($this->knowledge));
        Session::set('gameOn', true);
        Session::set('lastAnsware', '');
        $this->node = ['Pasta'];
        Session::setArray('node', $this->node);
    }
    public function initGame(): string
    {
        Session::clear();
        $this->knowledge = ['Pasta' => 'Lasagna' , 'Cake' => 'Chocolate Cake'];
        Session::setArray('knowledge', $this->knowledge);
        $this->init();
        return 'Think of a meal you like and then go to \'/start\'?';
    }
    public function startRound(): string
    {
        $this->init();
        return 'The meal you thoguht of is \'' . Session::get('last') . '\'? Go to \'/yes\' if so, \'/no\' otherwise.';
    }
    private function getNextLevel(bool $val = false): string
    {
        $message = 'Is it \'';
        if ($val) {
            $message .= $this->knowledge[Session::get('last')];
        } else {
            reset($this->knowledge);
            while ($key = key($this->knowledge)) {
                if ($key == Session::get('last')) {
                    next($this->knowledge);
                    Session::set('last', key($this->knowledge));
                    $message .= Session::get('last');
                    break;
                }
                next($this->knowledge);
            }
        }
        $message .= '\' the meal you thoguht of? If not go to \'/no\' otherwise play \'/yes\' again.';
        return $message;
    }
    private function isActiveRound(): bool
    {
        $gameOn = Session::get('gameOn');
        return $gameOn !== null;
    }
    private function noMoreOptions(): bool
    {
        return Session::get('last') == array_key_last($this->knowledge);
    }
    public function yes() : string
    {
        if ($this->isActiveRound()) {

            if (Session::get('lastAnsware') == 'y') {
                Session::set('gameOn', false);
                $message = 'Shotgun! Do you wanna keep on playing? If so, go to \'/start\'';
            } else {
                $message = $this->getNextLevel(true);
            }
            Session::set('lastAnsware', 'y');
        } else {
            $message = 'Plase, go to \'/start\' to start another round';
        }
        return $message;
    }
    public function no() : string
    {
        if ($this->isActiveRound()) {
            if (Session::get('lastAnsware') == 'y' || $this->noMoreOptions()) {
                $message = 'Ok, I give up! Go to \'/add\' and tell me your toght or go to \'/start\' for another round!';
            } else {
                Session::set('lastAnsware', 'n');
                $message = $this->getNextLevel();
            }
        } else {
            $message = 'Plase, go to \'/start\' to start another round';
        }
        return $message;
    }
    public function addLink($link) : string
    {
        if ($this->isActiveRound()) {
            if ($link == null) {
                $message = 'Plase, you must provide a \'name\' and a \'what\' values!';
            } else if (isset($this->knowledge[$link['what']]) && $this->knowledge[$link['what']] == $link['name']) {
                $message = 'I already know that! Please, provide another one.';
            } else if (isset($this->knowledge[$link['what']])) {
                $message = 'I already know \'' . $link['what'] . '\' but it is \'' . $this->knowledge[$link['what']] . '\' not \'' . $link['name'] . '\'!';
            } else {
                $this->knowledge = [$link['what'] => $link['name']] + $this->knowledge;
                Session::setArray('knowledge', $this->knowledge);
                $message = 'Thanks to teatch me. Now lets play again? Go to \'/start\' for another round!';
            }
        } else {
            $message = 'Plase, go to \'/start\' to start another round';
        }
        return $message;
    }
}
