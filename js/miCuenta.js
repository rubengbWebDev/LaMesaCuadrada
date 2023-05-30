function obtenerValorCookie(nombre) {
    var nombreCookie = nombre + "=";
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(nombreCookie) === 0) {
            return cookie.substring(nombreCookie.length, cookie.length);
        }
    }
    return "";
}

primeraVez = undefined;
let usuario;

function generarUsuariosYBotones(primeraVez) {

    let correoUsuario = obtenerValorCookie("correo");

    if (primeraVez == undefined) {

        $.ajax({
            url: 'http://localhost:8001/usuario',
            method: 'GET',
            data: {
                email: correoUsuario
            },
            success: function (response) {

                usuario = response;
                muestraUsuarios(usuario);
                eventoBotonesPaginacion();
            },
            error: function (xhr, status, error) {

                console.log(error);
            }
        });

        primeraVez = false;
    }
}

function muestraUsuarios(usuario) {

    var usuarioObjeto = JSON.parse(usuario);
    let listaUsuarios = $('#lista-usuarios'); // Contenedor de la lista de usuarios

    listaUsuarios.empty();

    var card = $('<div>').addClass('card mb-3 bg-dark text-white');
    var cardBody = $('<div>').addClass('card-body p-0');
    var row = $('<div>').addClass('row m-0 align-items-center');
    var col1 = $('<div>').addClass('col p-2 border-end text-center').text("nombre");
    var col2 = $('<div>').addClass('col p-2 border-end text-center').text("Email");
    var col3 = $('<div>').addClass('col p-2 border-end text-center').text('Fecha de creacion');

    row.append(col1, col2, col3);
    cardBody.append(row);
    card.append(cardBody);
    var colWrapper = $('<div>').addClass('col-12').append(card);
    listaUsuarios.append(colWrapper);

    var card = $('<div>').addClass('card mb-3');
    var cardBody = $('<div>').addClass('card-body p-0');
    var row = $('<div>').addClass('row m-0 align-items-center');
    var col1 = $('<div>').addClass('col p-2 border-end text-center').text(usuarioObjeto.nombre);
    var col2 = $('<div>').addClass('col p-2 border-end text-center').text(usuarioObjeto.email);
    var col3 = $('<div>').addClass('col p-2 border-end text-center').text(usuarioObjeto.fechaCreacion);

    row.append(col1, col2, col3);
    cardBody.append(row);

    //CardFooter
    var cardFooter = $('<div>').addClass('card-footer p-2  text-center');

    var cambiarCorreoBtn = $('<button>').addClass('btn btn-primary me-2').text('Cambiar correo');
    cambiarCorreoBtn.on('click', function () {
        cambiarCorreo(); // Llamada a la función para eliminar el usuario
    });

    var cambiarContrasenaBtn = $('<button>').addClass('btn btn-danger').text('Cambiar contraseña');
    cambiarContrasenaBtn.on('click', function () {
        cambiarContrasena(); // Llamada a la función para eliminar el usuario
    });
    cardFooter.append(cambiarCorreoBtn, cambiarContrasenaBtn);

    card.append(cardBody, cardFooter);
    var colWrapper = $('<div>').addClass('col-12').append(card);
    listaUsuarios.append(colWrapper);
    listaUsuarios.addClass('d-flex flex-wrap justify-cotente-center');
}

function eventoBotonesPaginacion() {

    $(document).ready(function () {
        $("#siguiente").hide();
        $("#final").hide();
        $("#inicio").hide();
        $("#anterior").hide();
        $("#mostrando").hide();
        $("#paginacion-borde").hide();
    });
}

function cambiarContrasena() {

    // Obtener los datos de la partida
    let correo = obtenerValorCookie("correo");

    // Crear el modal de edición
    let modal = $('<div>').addClass('modal fade').attr('id', 'modalEditar');
    let modalDialog = $('<div>').addClass('modal-dialog');
    let modalContent = $('<div>').addClass('modal-content');
    let modalHeader = $('<div>').addClass('modal-header');
    let modalTitle = $('<h5>').addClass('modal-title').text('Cambiar correo');
    let modalBody = $('<div>').addClass('modal-body');



    // Crear el formulario de edición
    let form = $('<form>').addClass('needs-validation').attr('id', 'formularioEditar').attr('novalidate', true);
    let contrasenaVieja = $('<input>').attr('type', 'password').addClass('form-control mb-3').attr('name', 'contrasenaVieja');
    let contrasenaNueva = $('<input>').attr('type', 'password').addClass('form-control mb-3').attr('name', 'contrasenaNueva');
    let contrasenaRepetida = $('<input>').attr('type', 'password').addClass('form-control mb-3').attr('name', 'contrasenaRepetida');


    // Agregar los elementos del formulario al modal
    form.append($('<label>').text('Al cambiar la contraseña se cerrará su sesión y se redirigirá a Noticias, añada su antigua contraseña: ')).append(contrasenaVieja);
    form.append($('<label>').text('Ahora introduzca la nueva contraseña: ')).append(contrasenaNueva);
    form.append($('<label>').text('Repita la nueva contraseña: ')).append(contrasenaRepetida);

    let mensajeError = $('<p>').addClass('text-danger text-center').attr('id', 'mensajeError');
    modalBody.append(form).append(mensajeError);
    // Crear los botones de cancelar y confirmar cambios
    let cancelarButton = $('<button>').addClass('btn btn-danger close btn').attr('type', 'button').attr('data-dismiss', 'modal').text('Cancelar');
    let confirmarButton = $('<button>').addClass('btn btn-primary').attr('type', 'button').text('Confirmar Cambios');

    var buttonContainer = $('<div>').addClass('d-flex justify-content-center');
    buttonContainer.append(cancelarButton).append($('<div>').addClass('mx-2')).append(confirmarButton);

    // Agregar los botones al modal
    var modalFooter = $('<div>').addClass('modal-footer d-flex justify-content-center');
    modalFooter.append(buttonContainer);

    // Construir la estructura del modal
    modalHeader.append(modalTitle);
    // $modalHeader.append($modalTitle).append($modalCloseButton);

    modalContent.append(modalHeader).append(modalBody).append(modalFooter);
    modalDialog.append(modalContent);
    modal.append(modalDialog);

    // Agregar el modal al documento
    $('body').append(modal);

    // Mostrar el modal de edición
    $('#modalEditar').modal('show');

    //BOTONES DEL MODAL

    // Evento click en el botón "Cancelar"
    cancelarButton.on('click', function () {
        // Cerrar y eliminar el modal de edición
        $('#modalEditar').modal('hide').remove();
    });

    // Evento click en el botón "Confirmar Cambios"
    confirmarButton.on('click', function () {

        let contrasenaNuevaCompara1 = $('[name="contrasenaNueva"]').val();
        let contrasenaNuevaCompara2 = $('[name="contrasenaRepetida"]').val();

        if (contrasenaNuevaCompara1 != contrasenaNuevaCompara2) {

            mensajeError.text('Las contraseñas nueva y su repetición no coinciden').show();
        } else {

            // Obtener los datos actualizados del formular

            let correoAntiguo2 = obtenerValorCookie("correo");
            let contrasenaVieja2 = $('[name="contrasenaVieja"]').val();
            let conntrasenaNueva2 = $('[name="contrasenaNueva"]').val();


            // Realizar la petición PUT mediante AJAX
            $.ajax({
                url: 'http://localhost:8001/usuario',
                type: 'PUT',
                dataType: 'json',
                data: JSON.stringify({
                    correo: correoAntiguo2,
                    contrasena: contrasenaVieja2,
                    contrasena_nueva: conntrasenaNueva2
                }),
                success: function (response) {

                    $.ajax({
                        type: 'POST',
                        url: 'http://localhost:8001/logout',
                        success: function (response) {
                            console.log("Adios colega");
                            document.cookie = "correo" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            document.cookie = "nombre" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            document.cookie = "tipo" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                            window.location.href = '../index.php';
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                        }
                    });
                },
                error: function (xhr, status, error) {

                    console.error(error);
                }
            });
        }
    });
}

function cambiarCorreo() {

    // Obtener los datos de la partida

    // Crear el modal de edición
    let modal = $('<div>').addClass('modal fade').attr('id', 'modalEditar');
    let modalDialog = $('<div>').addClass('modal-dialog');
    let modalContent = $('<div>').addClass('modal-content');
    let modalHeader = $('<div>').addClass('modal-header');
    let modalTitle = $('<h5>').addClass('modal-title').text('Cambiar correo');
    let modalBody = $('<div>').addClass('modal-body');

    // Crear el formulario de edición
    let form = $('<form>').addClass('needs-validation').attr('id', 'formularioEditar').attr('novalidate', true);
    let correoNuevo = $('<input>').attr('type', 'text').addClass('form-control mb-3').attr('name', 'correo_nuevo');

    // Agregar los elementos del formulario al modal
    form.append($('<label>').text('Al cambiar el correo se cerrará su sesión y se redirigirá a Noticias, añade el nuevo correo: ')).append(correoNuevo);

    modalBody.append(form);

    // Crear los botones de cancelar y confirmar cambios
    let cancelarButton = $('<button>').addClass('btn btn-danger close btn').attr('type', 'button').attr('data-dismiss', 'modal').text('Cancelar');
    let confirmarButton = $('<button>').addClass('btn btn-primary').attr('type', 'button').text('Confirmar Cambios');

    var buttonContainer = $('<div>').addClass('d-flex justify-content-center');
    buttonContainer.append(cancelarButton).append($('<div>').addClass('mx-2')).append(confirmarButton);

    // Agregar los botones al modal
    var modalFooter = $('<div>').addClass('modal-footer d-flex justify-content-center');
    modalFooter.append(buttonContainer);

    // Construir la estructura del modal
    modalHeader.append(modalTitle);
    // $modalHeader.append($modalTitle).append($modalCloseButton);

    modalContent.append(modalHeader).append(modalBody).append(modalFooter);
    modalDialog.append(modalContent);
    modal.append(modalDialog);

    // Agregar el modal al documento
    $('body').append(modal);

    // Mostrar el modal de edición
    $('#modalEditar').modal('show');

    //BOTONES DEL MODAL

    // Evento click en el botón "Cancelar"
    cancelarButton.on('click', function () {
        // Cerrar y eliminar el modal de edición
        $('#modalEditar').modal('hide').remove();
    });

    // Evento click en el botón "Confirmar Cambios"
    confirmarButton.on('click', function () {
        // Obtener los datos actualizados del formular

        let correoAntiguo2 = obtenerValorCookie("correo");
        let correoNuevo2 = $('[name="correo_nuevo"]').val();

        // Realizar la petición PUT mediante AJAX
        $.ajax({
            url: 'http://localhost:8001/usuario',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify({
                correo: correoAntiguo2,
                correo_nuevo: correoNuevo2
            }),
            success: function (response) {

                $.ajax({
                    type: 'POST',
                    url: 'http://localhost:8001/logout',
                    success: function (response) {
                        console.log("Adios colega");
                        document.cookie = "correo" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "nombre" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        document.cookie = "tipo" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

                        window.location.href = '../index.php';
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            },
            error: function (xhr, status, error) {

                console.error(error);
            }
        });
    });
}

$(document).ready(function () {

    generarUsuariosYBotones(primeraVez);
});