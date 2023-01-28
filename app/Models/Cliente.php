<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'cpf'];

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:30',
            'cpf' => 'required|', Rule::unique('clientes')->ignore($this->id, 'id')
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',

            'nome.min' => 'O campo nome deve ter no minímo 3 caracteres',
            'nome.max' => 'O campo nome deve ter no maxímo 30 caracteres',

            'cpf.unique' => 'Já existe um cliente cadastrado com esse CPF',

            'cpf.min' => 'O campo CPF deve ter 14 caracteres',
            'cpf.max' => 'O campo CPF deve ter 14 caracteres'
        ];
    }
}
