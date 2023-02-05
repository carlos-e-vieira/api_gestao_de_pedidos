<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Repositories\FornecedorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate(
            $this->fornecedor->regrasValidacao(),
            $this->fornecedor->mensagemValidacao()
        );

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

    public function update(Request $request, $id)
    {
        $fornecedor = $this->fornecedor->find($id);

        if ($fornecedor === null) {
            return response()->json(['success' => false], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate(
                $this->fornecedor->regrasValidacao(),
                $this->fornecedor->mensagemValidacao()
            );
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($fornecedor->regrasValidacao() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $this->fornecedor->mensagemValidacao());
        }

        // remove o arquivo de imagem antigo caso um novo seja enviado na request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($fornecedor->imagem);
        }

        $imagem = $request->file('imagem');
        $imagemPath = $imagem->store('imagens', 'public');

        // Preencher objeto $fornecedor com os dados da request
        $fornecedor->fill($request->all());
        $fornecedor->imagem = $imagemPath;
        $fornecedor->save();

        return response()->json($fornecedor, 200);
    }

    public function destroy($id)
    {
        $fornecedor = $this->fornecedor->find($id);

        if ($fornecedor === null) {
            return response()->json(['success' => false], 404);
        }

        // Remove o arquivo de imagen antigo
        Storage::disk('public')->delete($fornecedor->imagem);

        $fornecedor->delete();
        return response()->json(['success' => true], 200);
    }
}
