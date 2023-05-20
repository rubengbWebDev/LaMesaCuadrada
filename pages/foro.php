<?php
session_start();


if (!isset($_SESSION['usuario'])) {
    header("Location foro.php");

    $_SESSION['token_login'] = bin2hex(random_bytes(16));
    $_SESSION['token_registro'] = bin2hex(random_bytes(16));
}
print_r($_SESSION);
print_r($_COOKIE);
if (isset($_COOKIE['correo'])) {

    $_SESSION['usuario'] = $_COOKIE['correo'];
    $_SESSION['usuario_tipo'] = $_COOKIE['tipo'];
    $_SESSION['nombre'] = $_COOKIE['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Foro - La Mesa Cuadrada</title>
    <link rel="stylesheet" href="./../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/foro.css" />
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg navbar-transparent">

        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../img/logo.png" alt="logo" width="50em" height="50em">
                <b>La Mesa Cuadrada</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse justify-content-start">
                <div class="navbar-nav text-light">
                    <a href="../index.php" class="nav-item nav-link active">Actualidad</a>
                    <a href="foro.php" class="nav-item nav-link ">Foro</a>
                    <a href="tienda.html" class="nav-item nav-link ">Tienda</a>
                    <a href="registro_partidas.html" class="nav-item nav-link ">Registro de Partidas</a>
                </div>

                <div class="navbar-nav ms-auto ml-auto action-buttons">

                    <?php if (!isset($_SESSION['usuario'])) : ?>
                        <div class="nav-item dropdown pr-2">
                            <a href="#" role="button" data-bs-toggle="dropdown" class="btn btn-success dropdown-toggle sign-up-btn movida">Login</a>
                            <div class="dropdown-menu action-form">
                                <form id="login-form" action="api/controller" method="post">
                                    <!-- value=\"{$_SESSION['token']}\" -->
                                    <input type="hidden" name="token_login" value="<?= $_SESSION['token_login'] ?>">
                                    <input type="hidden" name="login">
                                    <div class="form-group errorLogin">
                                        <input type="text" name="correo" class="form-control" placeholder="Usuario" required="required">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required="required">
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-block" value="Login">
                                    <div class="text-center mt-2">
                                        <a href="#">¿Olvidaste tu contraseña?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#login-form').submit(function(event) {
                                    event.preventDefault(); // Evitar envío predeterminado del formulario

                                    // Obtener los datos del formulario
                                    var formData = $(this).serialize();

                                    // Realizar la solicitud AJAX
                                    $.ajax({
                                        type: 'POST',
                                        url: 'http://localhost:8001/login',
                                        data: formData,
                                        success: function(response) {

                                            if (response == "null") {

                                                $(document).ready(function() {
                                                    $('.errorLogin').before('<p id="error-msg">Datos erroneos</p>');
                                                    $('#error-msg').css('color', 'red');
                                                });

                                            } else {
                                                console.log(response);
                                                let userData = JSON.parse(response); // Analizar la respuesta JSON

                                                let nombre = userData.usuario_nombre;
                                                let email = userData.usuario_email;
                                                let tipo = userData.usuario_tipo;

                                                document.cookie = "nombre=" + nombre + "; path=/";
                                                document.cookie = "correo=" + email + "; path=/";
                                                document.cookie = "tipo=" + tipo + "; path=/";

                                                window.location.href = 'foro.php';
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.log(error);
                                        }
                                    });
                                });
                            });
                        </script>

                        <div class="nav-item dropdown" id="movida">
                            <a href="#" role="button" data-bs-toggle="dropdown" class="btn btn-primary dropdown-toggle sign-up-btn">Registrarse</a>
                            <div class="dropdown-menu action-form">
                                <form id="registro" action="api/controller" method="post">
                                    <p class="hint-text">Rellena el formulario para crear tu cuenta</p>
                                    <input type="hidden" name="token_registro" value="<?= $_SESSION['token_registro'] ?>">
                                    <input type="hidden" name="registro">
                                    <div class="form-group errorRegistro">
                                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="correo" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Confirma Contraseña" required>
                                    </div>
                                    <div class="form-group">
                                        <label id="propagacion" class="form-check-label">
                                            <input type="checkbox" required> Acepto las <a href="#">Terminos &amp;
                                                Condiciones</a>
                                        </label>
                                    </div>
                                    <input id="boton-registro" type="submit" class="btn btn-primary btn-block" value="Registrarse" disabled>
                                </form>
                            </div>
                        </div>

                        <script>
                            /*MIRAR SI EL FORMULARIO ESTA CUMPLIMENTO*/
                            $(document).ready(function() {
                                // Escuchar el evento de cambio en los campos del formulario
                                $('#registro input').on('change', function() {
                                    var formCompleto = true;

                                    // Verificar si hay algún campo vacío
                                    $('#registro input[required]').each(function() {
                                        if ($(this).val() === '') {
                                            formCompleto = false;
                                            return false; // Salir del bucle each si hay un campo vacío
                                        }
                                    });

                                    // Habilitar o deshabilitar el botón de registro según el estado del formulario
                                    if (formCompleto) {
                                        $('#boton-registro').prop('disabled', false);
                                    } else {
                                        $('#boton-registro').prop('disabled', true);
                                    }
                                });
                            });

                            /*PETICION*/
                            $(document).ready(function() {
                                $('#registro').submit(function(event) {
                                    event.preventDefault(); // Evitar envío predeterminado del formulario

                                    // Obtener los datos del formulario
                                    var formData = $(this).serialize();

                                    // Realizar la solicitud AJAX
                                    $.ajax({
                                        type: 'POST',
                                        url: 'http://localhost:8001/registro',
                                        data: formData,
                                        success: function(response) {

                                            if (response == "null") {

                                                $(document).ready(function() {
                                                    $('.errorRegistro').before('<p id="error-msg">Datos erroneos</p>');
                                                    $('#error-msg').css('color', 'red');
                                                });
                                            } else {

                                                let tiempoEspera = 2000;
                                                let urlDestino = window.location.href;
                                                alert("Usuario registrado con exito, pulse aceptar para ser redirigido a donde se encontraba");

                                                function redirigir() {
                                                    window.location.href = urlDestino;
                                                }
                                                setTimeout(redirigir, tiempoEspera);
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            // Manejar errores de la solicitud
                                            console.log(error);
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php else : ?>

                        <div class="nav-item dropdown pr-2">
                            <button class="btn btn-success btn-outline-dark opciones-btn" style="border: 3px solid black;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="texto-bienvenida"><?= "Hola " . $_SESSION['nombre'] ?></span>
                                <script>
                                    $(document).ready(function() {
                                        var nombreCookie = getCookie("nombre");
                                        var saludo = "Hola " + nombreCookie;

                                        $(".texto-bienvenida").text(saludo);
                                    });

                                    // Función para obtener el valor de una cookie por su nombre
                                    function getCookie(nombre) {
                                        var name = nombre + "=";
                                        var decodedCookie = decodeURIComponent(document.cookie);
                                        var cookies = decodedCookie.split(';');
                                        for (var i = 0; i < cookies.length; i++) {
                                            var cookie = cookies[i];
                                            while (cookie.charAt(0) == ' ') {
                                                cookie = cookie.substring(1);
                                            }
                                            if (cookie.indexOf(name) == 0) {
                                                return cookie.substring(name.length, cookie.length);
                                            }
                                        }
                                        return "";
                                    }
                                </script>
                                <span class="tres-puntos">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </span>
                            </button>
                            <form id="logout-form" method="post">
                                <div class="dropdown-menu action-form" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Mi cuenta</a>
                                    <a class="dropdown-item" href="#">Mis pedidos</a>
                                    <a class="dropdown-item" href="#">Mis pedidos</a>
                                    <input type="submit" class="btn btn-danger btn-block" style="border: 3px solid black;" value="Cerrar Sesión">
                                </div>
                            </form>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#logout-form').submit(function(event) {
                                    event.preventDefault(); // Evitar envío predeterminado del formulario

                                    // Obtener los datos del formulario
                                    var formData = $(this).serialize();

                                    // Realizar la solicitud AJAX
                                    $.ajax({
                                        type: 'POST',
                                        url: 'http://localhost:8001/logout',
                                        data: formData,
                                        success: function(response) {
                                            console.log("Adios colega");
                                            document.cookie = "correo" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                                            document.cookie = "nombre" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                                            document.cookie = "tipo" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                                            window.location.href = 'foro.php';
                                        },
                                        error: function(xhr, status, error) {
                                            console.log(error);
                                        }
                                    });
                                });
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>

        <div class="container-fluid">
            <div class="row justify-content-center mt-2 mb-2">
                <div class="col-lg-8 col-md-10 col-12">
                    <div class="accordion" id="hilos-accordion">
                        <div class="card">
                            <!-- 
                            <div class="card-header">
                                <h2 class="mb-0">
                                    <a href="#" class="text-decoration-none" data-bs-toggle="collapse" data-bs-target="#general3-hilos" aria-expanded="true" aria-controls="general3-hilos">
                                        General
                                    </a>
                                </h2>
                            </div>
                            <ul class="list-group list-group-flush collapse show" id="general3-hilos">
                                <li class="list-group-item">Hilo 1</li>
                                <li class="list-group-item">Hilo 2</li>
                                <li class="list-group-item">Hilo 3</li>
                            </ul>


                                <div class="card-header" data-bs-toggle="collapse" data-bs-target="#general2-hilos" aria-expanded="true" aria-controls="general2-hilos">
                                  <h2 class="mb-0">
                                    General
                                  </h2>
                                </div>
                                <div id="general2-hilos" class="collapse show">
                                  <div class="card-body">
                                    <ul class="list-group">
                                      <li class="list-group-item">
                                        Hilo 1
                                      </li>
                                      <li class="list-group-item">
                                        Hilo 2
                                      </li>
                                    </ul>
                                  </div>
                                </div> -->

                            <div class="card-header">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-decoration-none w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#general-hilos" aria-expanded="true" aria-controls="general-hilos">
                                        <h2>General</h2>
                                    </button>
                                </h2>
                            </div>

                            <div id="general-hilos" class="collapse show" aria-labelledby="headingOne">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    <strong>Jugando a Mansiones de la Locura y NO SE RICARDO</strong>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    <strong>Lo mejor sería fumarse un petardo mientras jugamos al
                                                        rescate??? [SEMI-SIRIO]</strong>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    <strong>Estaba con mi mano jugando al Codigo Secreto Duo y...
                                                        +18</strong>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Siguiente</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-decoration-none w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#experiencias-hilos" aria-expanded="false" aria-controls="experiencias-hilos">
                                        <h2>Experiencias</h2>
                                    </button>
                                </h2>
                            </div>

                            <div id="experiencias-hilos" class="collapse show" aria-labelledby="headingTwo">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Siguiente</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-decoration-none w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#analisis-hilos" aria-expanded="false" aria-controls="analisis-hilos">
                                        <h2>Análisis de Juegos</h2>
                                    </button>
                                </h2>
                            </div>

                            <div id="analisis-hilos" class="collapse show" aria-labelledby="headingTwo">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Siguiente</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-decoration-none w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#lanzamientos-hilos" aria-expanded="false" aria-controls="lanzamientos-hilos">
                                        <h2>Lanzamientos</h2>
                                    </button>
                                </h2>
                            </div>

                            <div id="lanzamientos-hilos" class="collapse show" aria-labelledby="headingTwo">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 1
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hola que tal me presento
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">13:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">pepito_19</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Quas ipsum quia dolore?
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">14:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">jugon4</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    badge bg-primary rounded-
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">tontito</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-10">
                                                    Hilo 5
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-primary rounded-pill">10:00</span>
                                                </div>
                                                <div class="col-sm-1 text-end">
                                                    <span class="badge bg-danger rounded-pill">Usuario1</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Siguiente</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-dark text-light py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>La Mesa Cuadrada &copy; 2023. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#">Contacto</a>
                    <a href="#">Política de privacidad</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        $("#propagacion").on("click", function(event) {
            event.stopPropagation();
        });
    </script>
</body>

</html>