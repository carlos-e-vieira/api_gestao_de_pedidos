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

    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
        $cliente = $this->cliente->create([
            'name' => $request->name,
            'cpf' => $request->cpf
        ]);

        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json($cliente, 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['success' => false], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->cliente->rules(), $this->cliente->feedback());
        }

        if ($request->method() === 'PATCH') {
            $dinamicsRules = array();

            foreach ($cliente->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }
            }

            $request->validate($dinamicsRules, $this->cliente->feedback());
        }

        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['success' => false], 404);
        }

        $cliente->delete();
        return response()->json(['success' => true], 200);
    }
}
