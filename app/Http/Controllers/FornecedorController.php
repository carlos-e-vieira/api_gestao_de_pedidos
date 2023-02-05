<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Repositories\FornecedorRepository;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    private Fornecedor $fornecedor;
    private FornecedorRepository $fornecedorRepository;

    public function __construct(Fornecedor $fornecedor)
    {
        $this->fornecedor = $fornecedor;
        $this->fornecedorRepository = new FornecedorRepository($this->fornecedor);
    }

    public function index(Request $request)
    {
        // condição de busca por atributos do produto
        if ($request->has('atributos_produtos')) {
            $atributos_produtos = 'produtos:id'. $request->atributos_produtos;
            $this->fornecedorRepository->selectAtributosRelacionados($atributos_produtos);
        } else {
            $this->fornecedorRepository->selectAtributosRelacionados('produtos');
        }

        // condição de busca com filtro
        if ($request->has('filtro')) {
            $this->fornecedorRepository->selectFiltros($request->filtro);
        }

        // condição de busca por atributos do forncecedor
        if ($request->has('atributos')) {
            $this->fornecedorRepository->selectAtributos($request->atributos);
        }

        return response()->json($this->fornecedorRepository->getResultado(), 200);
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

    public function show($id)
    {
        // adicionando o relacionamento - um fornecedor tem muitos produtos
        $fornecedor = $this->fornecedor->with('produtos')->find($id);

        if ($fornecedor === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json($fornecedor, 200);
    }
}
