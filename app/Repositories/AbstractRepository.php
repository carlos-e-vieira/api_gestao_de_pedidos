<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRelacionados($atributos)
    {
        // montando a query
        $this->model = $this->model->with($atributos);
    }

    public function selectFiltros($filtros)
    {
        $filtros = explode(';', $filtros);

        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}
