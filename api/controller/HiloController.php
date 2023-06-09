<?php
session_start();
require_once("../service/service_implement/HiloServiceImpl.php");
require_once("../service/service_implement/UsuarioServiceImpl.php");

switch ($_SERVER['REQUEST_METHOD']) {

    case "GET":

        if (isset($_GET['id'])) {
                
            $servicio = new HiloServiceImpl();
            $datos = $servicio->obtenerHiloPorId($_GET['id']);

            exit(json_encode($datos));

        } else {

            $servicio = new HiloServiceImpl();
            $datos = $servicio->obtenerHilosPorTipo($_GET['hilo_tipo']);

            if (count($datos) > 0) {
                exit(json_encode($datos));
            } else {
                echo "No hay datos";
            }

        }

        break;

    case "POST":

        if (isset($_POST["texto"]) && isset($_POST["titulo"]) && isset($_POST["publicar_hilo"])) {
            print_r($_SESSION['token_foro']);
            print_r($_POST['token_foro']);
            if ( $_SESSION["token_foro"] == $_POST["token_foro"]) {
                $texto = seguridadFormularios($_POST["texto"]);
                $titulo = seguridadFormularios($_POST["titulo"]);
    
                $servicio = new HiloServiceImpl();
                $id = $servicio->crear($_POST["texto"],"GENERAL",$_POST["titulo"],$_POST['nombre_usuario']);
                exit(json_encode($id));
            } else {
                exit(json_encode(null));
            }
            
        }

        break;

    case "PUT":
        
        $datos = json_decode(file_get_contents('php://input'));
        $servicio = new HiloServiceImpl();
        $actualizado = $servicio->update($datos->id, $datos->titulo, $datos->texto);
        exit(json_encode($actualizado));

    case "DELETE":
        
        $datos = json_decode(file_get_contents('php://input'));
        $servicio = new HiloServiceImpl();
        $actualizado = $servicio->delete($datos->id);
        header('Content-Type: application/json');

        exit(json_encode($actualizado));

        break;
}
