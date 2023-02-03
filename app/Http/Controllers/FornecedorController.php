<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    private Fornecedor $fornecedor;

    public function __construct(Fornecedor $fornecedor)
    {
        $this->fornecedor = $fornecedor;
    }

    public function index(Request $request)
    {

    }

    public function store(Request $request)
    {
        $request->validate($this->fornecedor->rules(), $this->fornecedor->feedback());

        $imagem = $request->file('imagem');
        $imagemPath = $imagem->store('imagens', 'public');

        $fornecedor = $this->fornecedor->create([
            'nome' => $request->nome,
            'imagem' => $imagemPath
        ]);

        return response()->json($fornecedor, 201);
    }
}
