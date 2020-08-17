<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Traits\ProvidesStorage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use App\Conversations\Conversacion1;
use BotMan\BotMan\Messages\Attachments\Video;

// base de datos
// detalles de la conexion
// $conn_string = "host=localhost port=5432 dbname=leenN user=postgres password=password3 options='--client_encoding=UTF8'";
 
// // establecemos una conexion con el servidor postgresSQL
// $dbconn = pg_connect($conn_string);
 
// // Revisamos el estado de la conexion en caso de errores. 
// if(!$dbconn) {
// echo "Error: No se ha podido conectar a la base de datos\n";
// } else {
// echo "Conexión exitosa\n";
// }


 



$botman = resolve('botman');
$storage = $botman->userStorage();

//Preguntas generales

$botman->hears('.*(hola|hola que tal|buenos días|buenos dias|buenas tardes|buenas noches).*', function ($bot) {
  $bot->typesAndWaits(1);
   $bot->reply('Hola!');
});

$botman->hears('.*(Como estas?|como te encuetras|que tal).*', function ($bot) {
  $bot->typesAndWaits(1);
   $bot->reply('Muy bien gracias!');
});
$botman->hears('.*(Que eres?|que eres).*', function ($bot) {
  $bot->typesAndWaits(1);
  $bot->reply('Soy un chatbot, un programa diseñado para ayudarte!');
});

$botman->hears('.*(Como te llamas?|quien eres?|Cual es tu nombre?).*', function ($bot) {
  $bot->typesAndWaits(1);
  $bot->reply('Mi nombre es botman :)');
});

$botman->hears('.*(Quien te hizo?|quien te creo?|quien te invento?).*', function ($bot) {
   $bot->typesAndWaits(1);
   $bot->reply('Fui programado por estudiantes de maestría del cinvestav');
});

$botman->hears('.*(Llamame|Mi nombre es|Me llamo|soy).* {name}', function ($bot, $name) {
  $bot->userStorage()->save([
        'name' => $name
    ]);
    $bot->typesAndWaits(1);
    $bot->reply('Hola  '.$name.' mucho gusto');
});

$botman->hears('.*(Quien soy?|quien soy yo?|como me llamo|cual es mi nombre).*', function ($bot) {
    $user = $bot->userStorage()->get();
    if ($user->has('name')) {
        $bot->reply('Tu eres '.$user->get('name'));
    } else {
        $bot->reply('No te conozco');
    }
});

$botman->hears('Yo tengo ([0-9]+) años', function ($bot, $number) {
    $bot->reply('Tu tienes  '.$number);
});

$botman->hears('.*(Mapa covid de mexico|mapa semaforo|mapa covid).*', function ($bot) {
// Create attachment
$attachment = new Image('https://as01.epimg.net/mexico/imagenes/2020/07/04/tikitakas/1593827555_121409_1593828263_sumario_normal.jpg', [
    'custom_payload' => true,
]);
// Build message object
$message = OutgoingMessage::create('Mapa de Semáforo de riesgo')
            ->withAttachment($attachment);
// Reply message object
$bot->reply($message);
});

//Preguntas del sistema leen

$botman->hears('.*(cuantas escuelas hay|Cuantas escuelas hay en el programa|¿Cuantas escuelas hay en el programa?|¿Cuantas escuelas existen en el programa?).*', function ($bot){
  // require_once 'dbconfig.php';
$host='localhost';
$db = 'leenN';
$username = 'postgres';
$password = 'password3';

// $dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

  try{
    // create a PostgreSQL database connection
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password");
    
    // display a message if connected to the PostgreSQL successfully
    if($conn){
      $query = 'select count(*) from escuela;';
      $result = $conn->query($query);
      $rows = $result->fetchColumn();

      $bot->typesAndWaits(1);
      $bot->reply('Hay '.$rows.' escuelas en el programa');
    // echo "Connected to the <strong>$db</strong> database successfully!";
    
    }else{
    $bot->reply('Sin conexión a la base de datos :(');
   }
   }catch (PDOException $e){
    // report error message
    $bot->reply('Error: '.$e->getMessage());
    // echo $e->getMessage();
   }
  
 
});

$botman->hears('.*(cuantos diagnósticos hay|Cuantos diagnósticos hay en el programa|¿Cuantos diagnósticos hay en el programa?|¿Cuantos diagnósticos existen en el programa?).*', function ($bot){
  
  $host='localhost';
  $db = 'leenN';
  $username = 'postgres';
  $password = 'password3';
  
  // $dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
  
    try{
      // create a PostgreSQL database connection
      $conn = new PDO("pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password");
      
      // display a message if connected to the PostgreSQL successfully
      if($conn){
        $query = 'select count(*) from diagnostico_plantel;';
        $result = $conn->query($query);
        $rows = $result->fetchColumn();
  
        $bot->typesAndWaits(1);
        $bot->reply('Hay '.$rows.' diagnósticos de escuelas');
      // echo "Connected to the <strong>$db</strong> database successfully!";
      
      }else{
      $bot->reply('Sin conexión a la base de datos :(');
     }
     }catch (PDOException $e){
      // report error message
      $bot->reply('Error: '.$e->getMessage());
      // echo $e->getMessage();
     }

 
  // $conexion = pg_connect("host=localhost dbname=leenN user=postgres password=password3");
  // $query = 'SELECT * FROM diagnostico_plantel';
  // $result = pg_query($conexion, $query);
  // $conexion1 = pg_connect("host=localhost dbname=leenN user=postgres password=password3");
  // $query1 = "INSERT INTO preguntas VALUES ('¿Cuantos diagnósticos hay en el programa?')";
  // pg_query($conexion1, $query1);
  // $rows = pg_num_rows($result);
  // $bot->typesAndWaits(1);
  // $bot->reply('Hay '.$rows.' diagnósticos de escuelas');
});

$botman->hears('.*(cuantos planes de trabajo hay|Cuantos planes de trabajo hay en el programa|¿Cuantos planes de trabajo hay en el programa?|¿Cuantos planes de trabajo existen en el programa?).*', function ($bot){
  $host='localhost';
  $db = 'leenN';
  $username = 'postgres';
  $password = 'password3';
  
  // $dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
  
    try{
      // create a PostgreSQL database connection
      $conn = new PDO("pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password");
      
      // display a message if connected to the PostgreSQL successfully
      if($conn){
        $query = 'select count(*) from plan_trabajo;';
        $result = $conn->query($query);
        $rows = $result->fetchColumn();
  
        $bot->typesAndWaits(1);
        $bot->reply('Hay '.$rows.' planes de trabajo de escuelas');
      // echo "Connected to the <strong>$db</strong> database successfully!";
      
      }else{
      $bot->reply('Sin conexión a la base de datos :(');
     }
     }catch (PDOException $e){
      // report error message
      $bot->reply('Error: '.$e->getMessage());
      // echo $e->getMessage();
     }

  // $conexion = pg_connect("host=localhost dbname=leenN user=postgres password=password3");
  // $query = 'SELECT * FROM plan_trabajo';
  // $result = pg_query($conexion, $query);
  // $conexion1 = pg_connect("host=localhost dbname=leenN user=postgres password=password3");
  // $query1 = "INSERT INTO preguntas VALUES ('¿Cuantos planes de trabajo hay en el programa?')";
  // pg_query($conexion1, $query1);
  // $rows = pg_num_rows($result);
  // $bot->typesAndWaits(1);
  // $bot->reply('Hay '.$rows.' planes de trabajo de escuelas');
});

$botman->hears('.*(cuantas rendiciones de cuentas hay|cuantas rendiciones de cuentas hay en el programa|¿cuantas rendiciones de cuentas hay en el programa?|¿cuantas rendiciones de cuentas existen en el programa?).*', function ($bot){
  $host='localhost';
  $db = 'leenN';
  $username = 'postgres';
  $password = 'password3';
  
  // $dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
  
    try{
      // create a PostgreSQL database connection
      $conn = new PDO("pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password");
      
      // display a message if connected to the PostgreSQL successfully
      if($conn){
        $query = 'select count(*) from rendicion_cuentas;';
        $result = $conn->query($query);
        $rows = $result->fetchColumn();
  
        $bot->typesAndWaits(1);
        $bot->reply('Hay '.$rows.' rendiciones de cuentas de escuelas');
      // echo "Connected to the <strong>$db</strong> database successfully!";
      
      }else{
      $bot->reply('Sin conexión a la base de datos :(');
     }
     }catch (PDOException $e){
      // report error message
      $bot->reply('Error: '.$e->getMessage());
      // echo $e->getMessage();
     }

 
  // $conexion = pg_connect("host=localhost dbname=leenN user=postgres password=password3");
  // $query = 'SELECT * FROM rendicion_cuentas';
  // $result = pg_query($conexion, $query);
  // $conexion1 = pg_connect("host=localhost dbname=leenN user=postgres password=password3");
  // $query1 = "INSERT INTO preguntas VALUES ('¿cuantas rendiciones de cuentas hay en el programa?')";
  // pg_query($conexion1, $query1);
  // $rows = pg_num_rows($result);
  // $bot->typesAndWaits(1);
  // $bot->reply('Hay '.$rows.' rendiciones de cuentas de escuelas');
});
/*Si el usuario introduce alguna palabra que no está en la lista anterior salta el siguiente mensaje*/
$botman->fallback(function($bot) {
  $bot->reply('Lo siento no te entiendo :(');
});

$botman->hears('(Ayuda|Necesito ayuda|Ayudame)', BotManController::class.'@startConversation');

//$botman->fallback(FailController::class.'@index');

// pg_close($dbconn);
