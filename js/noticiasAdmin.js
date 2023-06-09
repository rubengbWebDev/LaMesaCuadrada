let noticias = [];
let noticiasPorPagina = 3;
let numeroPaginas;
let primeraVez;
let paginaActual;
let pagina = -1;

/**
 * Funcion que obtiene datos de base de datos
 */
function generarNoticiasYBotones(primeraVez) {
    if (primeraVez == undefined) {
        $.ajax({
            url: 'http://localhost:8001/noticias',
            method: 'GET',
            dataType: 'json',
            success: function (response) {

                noticias = response;
                //Genera botones la primera vez
                numeroPaginas = Math.ceil(response.length / noticiasPorPagina);
                //PRIMERA CARGA
                pagina = 1;

                // Obtener la parte correspondiente de las noticias según la página seleccionada
                let inicio = (pagina - 1) * noticiasPorPagina; // Índice de inicio
                let fin = inicio + noticiasPorPagina; // Índice de fin (no inclusivo)
                let noticiasPagina = noticias.slice(inicio, fin);

                muestraNoticias(noticiasPagina);

                $(document).on('click', '.leerMas', function () {
                    var text = $(this).prev('.texto');
                    text.toggleClass('expanded');
                    if (text.hasClass('expanded')) {
                        $(this).text('Ver menos');
                    } else {
                        $(this).text('Ver más');
                    }
                });
                eventoBotonesPaginacion();
            },
            error: function (xhr, status, error) {
                console.log(error); // Manejar el error de acuerdo a tus necesidades
            }
        });
        primeraVez = false;
    }
}

/**
 * Funcion encargada de mostrar en el front toda la información obtenida en el back
 */
function muestraNoticias(noticias) {

    $("#noticias-contenido").empty();
    // document.body.scrollTop = 0;
    // document.documentElement.scrollTop = 0;
    $.each(noticias, function (index, obj) {
        var $card = $('<div>').addClass('card mb-3');
        var $img = $('<img>').addClass('card-img-top imagen').attr('src', obj.imagen).attr('alt', '...');
        var $cardBody = $('<div>').addClass('card-body');
        var $title = $('<h3>').addClass('card-title titulo').text(obj.titulo);
        var $text = $('<article>').addClass('card-text clamp-text texto').html(obj.texto.replace(/\n/g, "<br>"));
        var $leerMas = $('<button>').addClass('btn btn-sm btn-outline-primary mt-2 leerMas').text('Ver más');
        var $cardFooter = $('<div>').addClass('card-footer');
        var $fecha = $('<small>').addClass('text-muted fecha').text('Fecha de Publicación: ' + obj.fecha);

        $card.append($img);
        $cardBody.append($title);
        $cardBody.append($text);


        $cardBody.append($leerMas);
        $card.append($cardBody);
        $cardFooter.append($fecha);
        $card.append($cardFooter);

        var $container = $('<div>').addClass('container-fluid my-4').attr('id', obj.id);
        var $row = $('<div>').addClass('row');
        var $col = $('<div>').addClass('col-12 col-md-8 offset-md-2 mx-auto');

        // Contenedor para los botones
        var $buttonContainer = $('<div>').addClass('text-center');
        $buttonContainer.addClass('mx-2');

        // Botón Editar
        var $btnEditar = $('<button>').addClass('btn btn-sm btn-outline-secondary editar').text('Editar').attr('id', obj.id);

        $btnEditar.on('click', function () {
            var id = $(this).attr('id');
            editarNoticia(id);
        });

        // Botón Eliminar
        var $btnEliminar = $('<button>').addClass('btn btn-sm btn-outline-danger eliminar').text('Eliminar').attr('id', obj.id);

        $btnEliminar.on('click', function () {
            var id = $(this).attr('id');
            eliminarNoticia(id);
        });

        $buttonContainer.append($btnEditar);
        $buttonContainer.append($btnEliminar);
        $cardFooter.append($buttonContainer);

        $card.append($cardFooter);

        var $container = $('<div>').addClass('container-fluid my-4');
        var $row = $('<div>').addClass('row');
        var $col = $('<div>').addClass('col-12 col-md-8 offset-md-2 mx-auto');

        $col.append($card);
        $row.append($col);
        $container.append($row);

        $('#noticias-contenido').append($container);
        $("#mostrando").text("Mostrando la pagina " + pagina + " de " + numeroPaginas);
    });
    document.documentElement.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

    document.body.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

/**
 * Funcion encargada de evento botones paginacion
 */
function eventoBotonesPaginacion() {
    $(document).on("click", ".botones", function (event) {
        event.preventDefault();
        event.stopPropagation();
        boton = $(this).attr("id");

        switch (boton) {
            case "inicio":
                pagina = 1;
                inicio = (pagina - 1) * noticiasPorPagina; // Índice de inicio
                fin = inicio + noticiasPorPagina; // Índice de fin (no inclusivo)
                noticiasPagina = noticias.slice(inicio, fin);
                $("#inicio").prop("disabled", true);
                $("#anterior").prop("disabled", true);
                $("#siguiente").prop("disabled", false);
                $("#final").prop("disabled", false);
                muestraNoticias(noticiasPagina);
                break;
            case "anterior":
                pagina--;
                inicio = (pagina - 1) * noticiasPorPagina; // Índice de inicio
                fin = inicio + noticiasPorPagina; // Índice de fin (no inclusivo)
                noticiasPagina = noticias.slice(inicio, fin);
                if (pagina == 1) {
                    $("#inicio").prop("disabled", true);
                    $("#anterior").prop("disabled", true);
                    $("#siguiente").prop("disabled", false);
                    $("#final").prop("disabled", false);
                } else {
                    $("#inicio").prop("disabled", false);
                    $("#anterior").prop("disabled", false);
                    $("#siguiente").prop("disabled", false);
                    $("#final").prop("disabled", false);
                }
                muestraNoticias(noticiasPagina);
                break;
            case "siguiente":
                pagina++;
                inicio = (pagina - 1) * noticiasPorPagina; // Índice de inicio
                fin = inicio + noticiasPorPagina; // Índice de fin (no inclusivo)
                noticiasPagina = noticias.slice(inicio, fin);
                if (pagina == numeroPaginas) {
                    $("#siguiente").prop("disabled", true);
                    $("#final").prop("disabled", true);
                    $("#anterior").prop("disabled", false);
                    $("#inicio").prop("disabled", false);
                } else {
                    $("#inicio").prop("disabled", false);
                    $("#anterior").prop("disabled", false);
                    $("#siguiente").prop("disabled", false);
                    $("#final").prop("disabled", false);
                }
                muestraNoticias(noticiasPagina);
                break;
            case "final":
                pagina = numeroPaginas;
                inicio = (pagina - 1) * noticiasPorPagina; // Índice de inicio
                fin = inicio + noticiasPorPagina; // Índice de fin (no inclusivo)
                noticiasPagina = noticias.slice(inicio, fin);
                $("#siguiente").prop("disabled", true);
                $("#final").prop("disabled", true);
                $("#inicio").prop("disabled", false);
                $("#anterior").prop("disabled", false);
                muestraNoticias(noticiasPagina);
                break;
        }
    });
}

/**
 * Funcion encargada de editar noticias
 */
function editarNoticia(id) {
    // Obtener el card de la noticia correspondiente al ID
    var $card = $('[id="' + id + '"]').closest('.card');

    // Obtener los datos de la noticia del card
    var titulo = $card.find('.titulo').text();
    var texto = $card.find('.texto').html().trim();
    var imagen = $card.find('.imagen').attr('src');

    // Crear el modal de edición
    var $modal = $('<div>').addClass('modal fade').attr('id', 'modalEditar');
    var $modalDialog = $('<div>').addClass('modal-dialog');
    var $modalContent = $('<div>').addClass('modal-content');
    var $modalHeader = $('<div>').addClass('modal-header');
    var $modalTitle = $('<h5>').addClass('modal-title').text('Editar noticia');
    var $modalBody = $('<div>').addClass('modal-body');

    // Crear el formulario de edición
    var $form = $('<form>').addClass('needs-validation').attr('id', 'formularioEditar').attr('novalidate', true);
    var $id = $('<input>').attr('type', 'hidden').attr('name', 'id').val(id);
    var $tituloInput = $('<input>').attr('type', 'text').addClass('form-control mb-3').attr('name', 'titulo').val(titulo);
    var $textoTextarea = $('<textarea>').addClass('form-control mb-3').attr('name', 'texto').css('height', '300px').val(texto);
    var $imagenInput = $('<input>').attr('type', 'text').addClass('form-control mb-3').attr('name', 'imagen').val(imagen);

    // Agregar los elementos del formulario al modal
    $form.append($id);
    $form.append($('<label>').text('Imagen: ')).append($imagenInput);
    $form.append($('<label>').text('Título: ')).append($tituloInput);
    $form.append($('<label>').text('Texto: ')).append($textoTextarea);
    $modalBody.append($form);

    // Crear los botones de cancelar y confirmar cambios
    var $cancelarButton = $('<button>').addClass('btn btn-danger close btn').attr('type', 'button').attr('data-dismiss', 'modal').text('Cancelar');
    var $confirmarButton = $('<button>').addClass('btn btn-primary').attr('type', 'button').text('Confirmar Cambios');


    var $buttonContainer = $('<div>').addClass('d-flex justify-content-center');
    $buttonContainer.append($cancelarButton).append($('<div>').addClass('mx-2')).append($confirmarButton);

    // Agregar los botones al modal
    var $modalFooter = $('<div>').addClass('modal-footer d-flex justify-content-center');
    $modalFooter.append($buttonContainer);

    // Construir la estructura del modal
    $modalHeader.append($modalTitle);
    // $modalHeader.append($modalTitle).append($modalCloseButton);

    $modalContent.append($modalHeader).append($modalBody).append($modalFooter);
    $modalDialog.append($modalContent);
    $modal.append($modalDialog);

    // Agregar el modal al documento
    $('body').append($modal);

    // Mostrar el modal de edición
    $('#modalEditar').modal('show');

    //BOTONES DEL MODAL

    // Evento click en el botón "Cancelar"
    $cancelarButton.on('click', function () {
        // Cerrar y eliminar el modal de edición
        $('#modalEditar').modal('hide').remove();
    });

    // Evento click en el botón "Confirmar Cambios"
    $confirmarButton.on('click', function () {
        // Obtener los datos actualizados del formulario
        var nuevoTitulo = $tituloInput.val();
        var nuevoTexto = $textoTextarea.val();
        var nuevaImagen = $imagenInput.val();

        // Realizar la petición PUT mediante AJAX
        $.ajax({
            url: 'http://localhost:8001/noticias',
            type: 'PUT',
            dataType: 'json',
            data: JSON.stringify({
                id: id,
                titulo: nuevoTitulo,
                texto: nuevoTexto,
                imagen: nuevaImagen
            }),
            success: function (response) {

                window.location.href = 'index.php';
            },
            error: function (xhr, status, error) {

                console.error(error);
            }
        });
    });

}

/**
 * Funcion encargada de eliminar noticias
 */
function eliminarNoticia(id) {
    // Obtener el card de la noticia correspondiente al ID
    var $card = $('[id="' + id + '"]').closest('.card');

    // Crear el modal de edición
    var $modal = $('<div>').addClass('modal fade').attr('id', 'modalEditar');
    var $modalDialog = $('<div>').addClass('modal-dialog');
    var $modalContent = $('<div>').addClass('modal-content');
    var $modalHeader = $('<div>').addClass('modal-header');
    var $modalTitle = $('<h5>').addClass('modal-title').text('Borrar noticia');
    var $modalBody = $('<div>').addClass('modal-body text-center').text('¿Seguro que quiere eliminar esta noticia?');

    // Crear el formulario de edición
    var $form = $('<form>').addClass('needs-validation').attr('id', 'formularioEditar').attr('novalidate', true);
    var $id = $('<input>').attr('type', 'hidden').attr('name', 'id').val(id);

    // Agregar los elementos del formulario al modal
    $form.append($id);
    $modalBody.append($form);

    // Crear los botones de cancelar y confirmar cambios
    var $cancelarButton = $('<button>').addClass('btn btn-danger close btn').attr('type', 'button').attr('data-dismiss', 'modal').text('Cancelar');
    var $confirmarButton = $('<button>').addClass('btn btn-primary').attr('type', 'button').text('Confirmar Cambios');


    var $buttonContainer = $('<div>').addClass('d-flex justify-content-center');
    $buttonContainer.append($cancelarButton).append($('<div>').addClass('mx-2')).append($confirmarButton);

    // Agregar los botones al modal
    var $modalFooter = $('<div>').addClass('modal-footer d-flex justify-content-center');
    $modalFooter.append($buttonContainer);

    // Construir la estructura del modal
    $modalHeader.append($modalTitle);
    // $modalHeader.append($modalTitle).append($modalCloseButton);

    $modalContent.append($modalHeader).append($modalBody).append($modalFooter);
    $modalDialog.append($modalContent);
    $modal.append($modalDialog);

    // Agregar el modal al documento
    $('body').append($modal);

    // Mostrar el modal de edición
    $('#modalEditar').modal('show');

    //BOTONES DEL MODAL

    // Evento click en el botón "Cancelar"
    $cancelarButton.on('click', function () {
        // Cerrar y eliminar el modal de edición
        $('#modalEditar').modal('hide').remove();
    });

    // Evento click en el botón "Confirmar Cambios"
    $confirmarButton.on('click', function () {

        // Realizar la petición PUT mediante AJAX
        $.ajax({
            url: 'http://localhost:8001/noticias',
            type: 'DELETE',
            dataType: 'json',
            data: JSON.stringify({
                id: id
            }),
            success: function (response) {

                window.location.href = 'index.php';
            },
            error: function (xhr, status, error) {
                
                console.error(error);
            }
        });
    });

}

/**
 * Evento que genera todo por primera vez haciendo llamadas a las funciones
 */
$(document).ready(function () {

    generarNoticiasYBotones(primeraVez);
});
