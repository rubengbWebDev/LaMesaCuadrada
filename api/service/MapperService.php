<?php

require_once('../model/Mensaje.php');

interface MapperService {

    public function mensajeToDto($mensaje);

}