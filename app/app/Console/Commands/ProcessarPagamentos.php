<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pedido;
use Illuminate\Support\Facades\Http;

class ProcessarPagamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:processar-pagamentos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(){
		$accessToken = env('GATEWAY_ACCESS_TOKEN');

		$pedidos = Pedido::where('id_situacao', operator: 1)
			->whereHas('pagamento', fn($q) => $q->where('id_formapagto', 3))
			->whereIn('id_loja', function ($query) {
				$query->select('id_loja')
					->from('lojas_gateway')
					->where('id_gateway', 1);
			})
			->with(['pagamento', 'cliente'])
			->get();

		foreach ($pedidos as $pedido) {
			$pagamento = $pedido->pagamento;
			$cliente = $pedido->cliente;

			$body = [
				"external_order_id" => $pedido->id,
				"amount" => $pedido->getValorTotalComFrete(),
				"card_number" => $pagamento->num_cartao,
				"card_cvv" => (string) $pagamento->codigo_verificacao,
				"card_expiration_date" => $this->formataVencimento($pagamento->vencimento),
				"card_holder_name" => $pagamento->nome_portador,
				"customer" => [
					"external_id" => $cliente->id,
					"name" => $cliente->nome,
					"type" => $cliente->getTipoPessoaGateway(),
					"email" => $cliente->email,
					"documents" => [
						[
							"type" => $cliente->getTipoDocumento(),
							"number" => $cliente->cpf_cnpj
						]
					],
					"birthday" => date('Y-m-d', strtotime($cliente->data_nasc))
				]
			];

            dump($body);

			$response = Http::post(
				"https://apiinterna.ecompleto.com.br/exams/processTransaction?accessToken={$accessToken}",
				$body
			);

			$pagamento->retorno_intermediador = $response->body();
			$pagamento->save();

			$codigo = $response->json('Transaction_code');

			if (!$response->json('Error') && $codigo === '00') {
				$pedido->id_situacao = 2; 
			} elseif (in_array($codigo, ['03', '04'])) {
				$pedido->id_situacao = 3;
			}

			$pedido->save();
		}
	}

	private function formataVencimento($vencimento){
		return date('my', strtotime("01/" . $vencimento));
	}
}
