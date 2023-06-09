<?php

class Noticia {
    private $id;
    private $fecha;
    private $texto;
    private $imagen;
	private $titulo;

    public function __construct($id, $fecha, $texto, $imagen, $titulo) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->texto = $texto;
        $this->imagen = $imagen;
		$this->titulo = $titulo;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->imagen = $titulo;
    }

	public function toArray() {
        return [
            "id" => $this->id,
            "fecha" => $this->fecha,
            "texto" => $this->texto,
            "imagen" => $this->imagen,
			"titulo" => $this->titulo
        ];
    }
}