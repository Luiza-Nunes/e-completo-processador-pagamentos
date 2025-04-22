<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pedido extends Model{
  
  protected $table = 'pedidos';
  public $timestamps = false;

  public function pagamento(){
    return $this->hasOne(PedidoPagamento::class, 'id_pedido');
  }

  public function cliente(){
    return $this->belongsTo(Cliente::class, 'id_cliente');
  }

  public function getValorTotalComFrete(){
    return $this->valor_total + $this->valor_frete;
  }
}