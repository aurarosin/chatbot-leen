<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class Conversacion1 extends Conversation
{
    protected $firstname;

    protected $email;

    public function askFirstname()
    {
        $this->ask('Hola! Cual es tu primer nombre?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Mucho gusto '.$this->firstname);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('Una cosa - cual es email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Genial - que es lo que , '.$this->firstname);
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }
}