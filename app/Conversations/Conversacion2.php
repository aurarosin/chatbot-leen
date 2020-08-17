<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;


class Conversacion2 extends Conversation
 {
     protected $section;
     protected $name;
     protected $email;
     public function askName()
     {  
         //Pregunta del chat bot al usuario donde se obtiene la respuesta
         $this->ask('Antes que nada. ¿Cómo te llamas? Para dirigirme a ti', function(Answer $answer) {
             // Texto respuesta
             $this->name = $answer->getText();
             $this->say('Encantado, '.$this->name);
            $this->askWhatToDo();
         });
     }

     public function askWhatToDo(){
        //Se programa una pregunta donde se establacen dos respuestas por defecto y un fallback por si no es ninguna de las dos
        $question =  Question::create('¿Qué deseas hacer en mi blog?')
                        ->fallback('Lo siento pero...')
                        ->callbackId('que_quieres_hacer')
                        ->addButtons([Button::create('¿Ver todos los posts?')->value('all'),Button::create('¿Ver todas las categorías?')->value('categorias'),]);
        $this->ask($question, function(Answer $answer) {

          if ($answer->isInteractiveMessageReply()){
            $value = $answer->getValue();
            $text = $answer->getText();
               $this->say('Opcion, '.$value.' '.$text);
          }
        });
    }

     public function run()
     {
         // Función llamada cuando se inicia la conversación
         $this->askName();
     }
 }