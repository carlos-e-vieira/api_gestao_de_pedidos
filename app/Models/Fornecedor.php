<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    protected $fillable = ['nome', 'imagem'];

    public function regrasValidacao(): array
    {
        return [
            'nome' => 'required|', Rule::unique('fornecedores')->ignore($this->id, 'id'),
            'imagem' => 'required|file|mimes:png,jpg,jpeg'
        ];
    }

    public function mensagemValidacao(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',

            'nome.unique' => 'Já existe um fornecedor cadastrado com este nome',
            'imagem.mimes' => 'O arquivo deve ser PNG, JPG ou JPEG'
        ];
    }

    public function produtos()
    {
        // um fornecedor possui muitos produtos
        return $this->hasMany(Produto::class);
    }
}
