<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    private Cliente $cliente;
    private ClienteRepository $clienteRepository;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
        $this->clienteRepository = new ClienteRepository($this->cliente);
    }

    public function index(Request $request)
    {
        // codição de busca com filtro
        if ($request->has('filtro')) {
            $this->clienteRepository->selectFiltros($request->filtro);
        }

        // condição de busca com atributos
        if ($request->has('atributos')) {
            $this->clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($this->clienteRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
        $cliente = [
            'name' => $request->name,
            'cpf' => $request->cpf
        ];
        $this->cliente->create($cliente);

        return response()->json(['success' => true], 201);
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
