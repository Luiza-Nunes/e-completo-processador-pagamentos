<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Cliente extends Model{
  protected $table = 'clientes';
  public $timestamps = false;

  public function getTipoPessoaGateway(){
    return $this->tipo_pessoa === 'F' ? 'individual' : 'corporation';
  }

  public function getTipoDocumento(){
    $numero = preg_replace('/\D/', '', $this->cpf_cnpj);
    return strlen($numero) === 11 ? 'cpf' : 'rg';
  }
}