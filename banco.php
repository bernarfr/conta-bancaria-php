<?php
// Classe ContaBancaria com métodos privados depositar() e sacar()
// e métodos públicos que exibem o saldo anterior e o saldo atual.

class ContaBancaria
{
	private float $saldo;
	private string $titular;

	public function __construct(string $titular, float $saldoInicial = 0.0)
	{
		$this->titular = $titular;
		$this->saldo = $saldoInicial;
	}

	// método privado que realiza o depósito e retorna saldo anterior e atual
	private function depositar(float $valor): array
	{
		$anterior = $this->saldo;
		$this->saldo += $valor;
		return ['anterior' => $anterior, 'atual' => $this->saldo];
	}

	// método privado que realiza o saque e retorna saldo anterior e atual
	// lança exceção se saldo for insuficiente
	private function sacar(float $valor): array
	{
		$anterior = $this->saldo;
		if ($valor > $this->saldo) {
			throw new Exception("Saldo insuficiente para sacar R$ " . number_format($valor, 2, ',', '.'));
		}
		$this->saldo -= $valor;
		return ['anterior' => $anterior, 'atual' => $this->saldo];
	}

	// wrapper público para depósito que exibe saldo anterior e saldo atual
	public function realizarDeposito(float $valor): void
	{
		$res = $this->depositar($valor);
		echo "[Depósito] Titular: {$this->titular} | Valor: R$ " . number_format($valor, 2, ',', '.') .
			" | Saldo anterior: R$ " . number_format($res['anterior'], 2, ',', '.') .
			" | Saldo atual: R$ " . number_format($res['atual'], 2, ',', '.') . PHP_EOL;
	}

	// wrapper público para saque que exibe saldo anterior e saldo atual
	public function realizarSaque(float $valor): void
	{
		try {
			$res = $this->sacar($valor);
			echo "[Saque] Titular: {$this->titular} | Valor: R$ " . number_format($valor, 2, ',', '.') .
				" | Saldo anterior: R$ " . number_format($res['anterior'], 2, ',', '.') .
				" | Saldo atual: R$ " . number_format($res['atual'], 2, ',', '.') . PHP_EOL;
		} catch (Exception $e) {
			echo "[Erro] " . $e->getMessage() . PHP_EOL;
		}
	}

	// getter simples para saldo
	public function getSaldo(): float
	{
		return $this->saldo;
	}
}

// --- Exemplo de uso ---
if (PHP_SAPI === 'cli' || defined('STDIN')) {
	$conta = new ContaBancaria('João Silva', 1000.00);

	$conta->realizarDeposito(250.00);
	$conta->realizarSaque(100.00);
	$conta->realizarSaque(2000.00); // demonstra tentativa com saldo insuficiente

	echo "Saldo final: R$ " . number_format($conta->getSaldo(), 2, ',', '.') . PHP_EOL;
}

?>
