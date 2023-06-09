<?php
session_start();


if (!isset($_SESSION['usuario'])) {
    header("Location index.php");

    $_SESSION['token_login'] = bin2hex(random_bytes(16));
    $_SESSION['token_registro'] = bin2hex(random_bytes(16));
}
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
    <title>La Mesa Cuadrada</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="./css/index.css" />
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg navbar-transparent">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="img/logo.png" alt="logo" width="50em" height="50em">
                <b id="titulo">La Mesa Cuadrada</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse justify-content-start ">
                <div class="navbar-nav text-light">
                    <a href="index.php" class="nav-item nav-link active navegacion seleccionado">Actualidad</a>
                    <a href="pages/foro.php" class="nav-item nav-link navegacion">Foro</a>
                    <a href="pages/registro_partidas.php" class="nav-item nav-link navegacion">Registro de Partidas</a>
                </div>

                <div class="navbar-nav ms-auto ml-auto action-buttons">

                    <?php if (!isset($_SESSION['usuario'])) : ?>
                        <div class="nav-item dropdown pr-2">
                            <a href="#" role="button" data-bs-toggle="dropdown" class="btn btn-success dropdown-toggle sign-up-btn movida ">Iniciar sesión</a>
                            <div class="dropdown-menu action-form rounded" id="login-dropdown">
                                <form id="login-form" action="api/controller" method="post">
                                    <input type="hidden" name="token_login" value="<?= $_SESSION['token_login'] ?>">
                                    <input type="hidden" name="login">
                                    <div class="form-group errorLogin">
                                        <input type="text" name="correo" class="form-control" placeholder="Email" required="required">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required="required">
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-block" value="Iniciar sesión">
                                    <div class="text-center mt-2">
                                        <a href="pages/olvide_contrasena.php">¿Olvidaste tu contraseña?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script>
                            $(document).on("click", "#login-dropdown", function(event) {
                                event.stopPropagation();
                            });
                        </script>
                        <script src="./js/login.js"></script>
                        <div class="nav-item dropdown" id="movida">
                            <a href="#" role="button" data-bs-toggle="dropdown" class="btn btn-primary dropdown-toggle sign-up-btn">Registrarse</a>
                            <div class="dropdown-menu action-form rounded">
                                <form id="registro" action="api/controller" method="post">
                                    <p class="hint-text">Rellena el formulario para crear tu cuenta</p>
                                    <input type="hidden" name="token_registro" value="<?= $_SESSION['token_registro'] ?>">
                                    <input type="hidden" name="registro">
                                    <div class="form-group errorRegistro">
                                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                                        <p id="nombre-error" style="color: red;"></p>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="correo" class="form-control" placeholder="Email" required>
                                        <p id="correo-error" style="color: red;"></p>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="contrasena-repetir" class="form-control" placeholder="Confirma Contraseña" required>
                                        <p id="contrasena-error" style="color: red;"></p>
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

                        <script src="./js/registro.js"></script>
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
                                <div class="dropdown-menu action-form rounded" aria-labelledby="dropdownMenuButton">
                                    <?php if ($_SESSION['usuario_tipo'] == "a") : ?>
                                        <a class="dropdown-item" href="pages/mi_cuenta_admin.php">Mi cuenta personal</a>
                                        <a class="dropdown-item" href="pages/mi_cuenta.php">Gestion de usuarios</a>
                                    <?php else : ?>
                                        <a class="dropdown-item" href="pages/mi_cuenta.php">Mi cuenta</a>
                                    <?php endif; ?>
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

                                            window.location.href = 'index.php';
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
    <section class="container-fluid">
        <?php if (isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] == "a")) : ?>
            <div class="container-fluid my-4">
                <div class="row">
                    <div class="col-12 text-center mx-auto">
                        <button class=" btn btn-lg btn-primary" id="crearNoticias">Crear Noticia</button>
                    </div>
                </div>
                <hr>
                <div class="row d-none no-gutters" id="crearNoticiasForm">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="publicar-noticias">
                                    <input type="hidden" name="publicar">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label">Título de la noticia</label>
                                        <input type="text" class="form-control" id="tituloNoticia" name="titulo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="imagen" class="form-label">Enlace de la imagen de cabecera</label>
                                        <input type="text" class="form-control" id="texto" name="imagen">
                                    </div>
                                    <div class="mb-3">
                                        <label for="imagen" class="form-label">Contenido de la noticia</label>
                                        <textarea class="form-control square-textarea" id="imagen" name="texto"></textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-danger" id="cancelarNoticia">Cancelar</button>
                                        <input type="submit" class="btn btn-primary btn-block" value="Publicar">
                                    </div>
                                </form>
                                <!-- CREAR NOTICIAS -->
                                <script>
                                    const crearNoticias = document.querySelector('#crearNoticias');
                                    const crearNoticiasForm = document.querySelector('#crearNoticiasForm');
                                    const cancelarNoticia = document.querySelector('#cancelarNoticia');

                                    crearNoticias.addEventListener('click', () => {
                                        crearNoticias.classList.add('d-none');
                                        crearNoticiasForm.classList.remove('d-none');
                                    });

                                    cancelarNoticia.addEventListener('click', () => {
                                        crearNoticiasForm.classList.add('d-none');
                                        crearNoticias.classList.remove('d-none');
                                    });
                                </script>
                                <script src="./js/crearNoticias.js"></script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>


    <main class="container-fluid">

        <div id="noticias-contenido"></div>
        <div id="paginacion-contenedor" class="d-flex justify-content-center  p-3">
            <div id="paginacion-borde" class="w-20 border rounded bg-white p-3">
                <p id="mostrando"></p>
                <div id="paginacion-botones" class="d-flex justify-content-center">
                    <button type="button" id="inicio" class="botones btn btn-sm btn-primary text-white fw-bold" disabled>
                        <span class="bi bi-chevron-bar-left"></span>
                    </button>

                    <button type="button" id="anterior" class="botones btn btn-sm btn-primary text-white fw-bold" disabled>
                        <span class="bi bi-chevron-left"></span>
                    </button>

                    <button type="button" id="siguiente" class="botones btn btn-sm btn-primary text-white fw-bold">
                        <span class="bi bi-chevron-right"></span>
                    </button>

                    <button type="button" id="final" class="botones btn btn-sm btn-primary text-white fw-bold">
                        <span class="bi bi-chevron-bar-right"></span>
                    </button>
                </div>
            </div>
        </div>

    </main>

    <!-- Carga de noticias y paginacion -->
    <div class="pagination"></div>

    <?php if (!isset($_SESSION['usuario_tipo'])) : ?>

        <script src="./js/noticias.js"></script>

    <?php elseif ($_SESSION['usuario_tipo'] == "u") : ?>

        <script src="./js/noticias.js"></script>

    <?php else : ?>

        <script src="./js/noticiasAdmin.js"></script>

    <?php endif; ?>


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
        $('.read-more-btn').click(function() {
            var text = $(this).prev();
            text.toggleClass('expanded');
            if (text.hasClass('expanded')) {
                $(this).text('Ver menos');
            } else {
                $(this).text('Ver más');
            }
        });
        $("#propagacion").on("click", function(event) {
            event.stopPropagation();
        });

        //Tema de ver mas del body de las noticias
        const readMoreButtons = document.querySelectorAll('.leerMas');
        readMoreButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const text = btn.previousElementSibling;
                text.classList.toggle('expanded');
                if (text.classList.contains('expanded')) {
                    btn.textContent = 'Ver menos';
                } else {
                    btn.textContent = 'Ver más';
                }
            });
        });
    </script>

    <!-- BOTON SCROLL -->
    <button onclick="topFunction()" id="scrollBtn" class="btn btn-primary rounded-circle"><i class="bi bi-arrow-up"></i></button>
    <script src="./js/botonScroll.js"></script>
</body>

</html>