<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;


class ExampleConversation extends Conversation
{
    public $firstname;
    public $email;

    public function askName()
    {
        $this->ask('Hola! Cuál es tu nombre? ', function(Answer $answer) {
            // Guardar
            $this->firstname = $answer->getText();
            $this->say('Mucho gusto '.$this->firstname);
            $this->askEmail();
        });
    }

    public function stopsConversation(IncomingMessage $message)
    {
        if ($message->getText() == 'stop') {
            return true;
        }
        return false;
    }

    public function askEmail()
    {
        $this->ask('Una cosa mas, cual es tu Email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();
            $this->say('Genial, muchas gracias, '.$this->firstname);
        });
    }
     
    public function askReason()
    {
        $question = Question::create("En que puedo ayudarte?")
            ->fallback('Incapaz de hacer una pregunta')
            ->callbackId('preguntar razón')
            ->addButtons([
                Button::create('¿Cuantas escuelas hay en el programa?')->value('joke'),
                Button::create('¿Cuantos presupuestos hay?')->value('quote'),
            ]);
            
    return $this->ask($question, function(Answer $answer){
        if($answer->isInteractiveMessageReply()){
            if ($answer->getValue() === 'joke'){
                $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                $this->say($joke->value->joke);
            }else{
                $this->say(Inspiring::quote());
            }
        } 
    }); 
    }

    public function hello()
    {
        $question = Question::create("¡Hola! Elige una opción") //Saludamos al usuario
            ->fallback('Incapaz de hacer una pregunta')
            ->callbackId('preguntar razón')
            ->addButtons([
                Button::create('¿Quién eres?')->value('who'),//Primera opcion, esta tendra el value who
                Button::create('¿Qué puedes decirme?')->value('info'), //Segunda opcion, esta tendra el value info
            ]);
        //Cuando el usuario elija la respuesta, se enviará el value aquí:
        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'who') {//Si es el value who, contestará con este mensaje
                    $this->say('Soy un chatbot, te ayudo a navegar por esta aplicación,                  solo debes escribir "Hola bot"');
                    //Si es el value info, llamaremos a la funcion options
                } else if ($answer->getValue() === 'info'){
                    $this->options();
                }
            }
        });
    }
    public function options(){
        $question = Question::create("¿Qué quieres saber?")//le preguntamos al usuario que quiere saber
            ->fallback('Unable to ask question')
            ->callbackId('preguntar razón')
            ->addButtons([
                Button::create('¿Qué hora es?')->value('hour'),//Opción de hora, con value hour
                Button::create('¿Qué día es hoy?')->value('day'),//Opción de fecha, con value day
            ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() === 'hour') {//Le muestra la hora la usuario si el value es hour
                        $hour = date('H:i');
                        $this->say('Son las '.$hour);
                    }else if ($answer->getValue() === 'day'){//Le muestra la hora la usuario si el value es date
                        $today = date("d/m/Y");
                        $this->say('Hoy es : '.$today);
                    }
                }
            });
    }


    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
        //$this->options();
        //$this->hello();
    }
}
