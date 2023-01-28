<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    private Cliente $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {
        $clientes = $this->cliente->all();
        return response()->json($clientes, 200);
    }

    public function store()
    {
        return 'store';
    }

    public function show($id)
    {
        return 'show';
    }

    public function update()
    {
        return 'update';
    }

    public function destroy()
    {
        return 'destry';
    }
}
