<?php

include "lib/medoo.php";

switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $database = new medoo([
    	// required
    	'database_type' => 'mysql',
    	'database_name' => 'gaveho',
    	'server' => 'mysql.gaveho.com',
    	'username' => 'gaveho',
    	'password' => '123456',
    	'charset' => 'utf8',
    	'prefix' => 'knockout_'
     ]);
     
    $last_user_id = $database->insert("users", [
	"identifier" => $email,
	"email" => $email,
	"password" => $password
    ]);
    
    header('Content-Type: application/json; charset=utf8');
    if($last_user_id > 0){
        echo json_encode([code=>"OK",message=>"Welcome, ".$email.". User successfully registered.",id=>$last_user_id]);
    }else{
        echo json_encode(array(code=>"FAIL",message=>"Sorry, an error has occurred. ".$database->error()[2],id=>$last_user_id,query=>$database->last_query()), JSON_PRETTY_PRINT);//
    }
    break;
    case "GET":
    echo json_encode(["method"=>"GET","GET"=>$_GET], JSON_PRETTY_PRINT);
    break;
    default:
    header("Location:../index.html");
    break;
}

exit;
require 'controllers/users.php';
//require 'controladores/contactos.php';
//require 'vistas/VistaXML.php';
require 'vistas/VistaJson.php';
require 'ExcepcionApi.php';

// Constantes de estado
const ESTADO_URL_INCORRECTA = 2;
const ESTADO_EXISTENCIA_RECURSO = 3;
const ESTADO_METODO_NO_PERMITIDO = 4;
print_r($_GET);

$vista = new VistaJson();

set_exception_handler(function ($exception) use ($vista) {
    $cuerpo = array(
        "estado" => $exception->estado,
        "mensaje" => $exception->getMessage()
    );
    if ($exception->getCode()) {
        $vista->estado = $exception->getCode();
    } else {
        $vista->estado = 500;
    }

    $vista->imprimir($cuerpo);
}
);

// Extraer segmento de la url
if (isset($_GET['PATH_INFO']))
    $peticion = explode('/', $_GET['PATH_INFO']);
else
    throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("No se reconoce la petición"));

// Obtener recurso
$recurso = array_shift($peticion);
$recursos_existentes = array('contactos', 'usuarios');

// Comprobar si existe el recurso
if (!in_array($recurso, $recursos_existentes)) {
    throw new ExcepcionApi(ESTADO_EXISTENCIA_RECURSO,
        "No se reconoce el recurso al que intentas acceder");
}

$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
    case 'post':
    case 'put':
    case 'delete':
        if (method_exists($recurso, $metodo)) {
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }
    default:
        // Método no aceptado
        $vista->estado = 405;
        $cuerpo = [
            "estado" => ESTADO_METODO_NO_PERMITIDO,
            "mensaje" => utf8_encode("Método no permitido")
        ];
        $vista->imprimir($cuerpo);

}