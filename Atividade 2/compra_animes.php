<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// sanitize helper
	function s($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

	// mask helper for display (keep only last 4)
	function mask_card($num){
		$n = preg_replace('/\D/','',$num);
		$len = strlen($n);
		if($len <= 4) return str_repeat('*', $len);
		return str_repeat('*', max(0, $len-4)) . substr($n, -4);
	}

	$posted = [
		'sexo'=>s($_POST['sexo'] ?? ''),
		'tenis_modelo'=>s($_POST['tenis_modelo'] ?? ''),
		'preco'=>s($_POST['preco'] ?? ''),
		'quantidade'=>s($_POST['quantidade'] ?? ''),
		// campos de pagamento
		'card_titular'=>s($_POST['card_titular'] ?? ''),
		'card_num'=>s($_POST['card_num'] ?? ''),
		'card_exp_month'=>s($_POST['card_exp_month'] ?? ''),
		'card_exp_year'=>s($_POST['card_exp_year'] ?? ''),
		'card_cvv'=>s($_POST['card_cvv'] ?? ''), // não será exibido
	];

	// preparar valores para exibição sem expor dados sensíveis
	$posted['card_num_masked'] = mask_card($posted['card_num']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8" />
	<title>Compra - Loja de Tênis Damante</title>
	<style>
		body{ font-family: Arial, sans-serif; background:#B0C4DE; color:#000; margin:20px; }
		.container{ max-width:980px; margin:0 auto; }
		h1{ text-align:right; font-size:20px; margin:0 0 10px 0; }
		fieldset{ background:transparent; border:1px solid #cfcfcf; padding:15px; margin-bottom:16px; }
		label{ display:inline-block; width:110px; vertical-align:top; margin-top:6px; }
		input[type="text"], input[type="password"], select{ padding:4px; }
		.small{ width:80px; }
		.medium{ width:360px; }
		.inline{ display:inline-block; margin-right:12px; }
		.actions{ margin-top:10px; }
		.summary{ background:#fff; padding:10px; border:1px solid #999; margin-bottom:16px; }
	</style>
</head>
<body>
	<div class="container">
		<h1>Compra - Loja de Tênis Damante</h1>

		<?php if (!empty($posted)): ?>
			<div class="summary">
				<strong>Resumo do envio:</strong><br/>
				SEXO: <?= $posted['sexo'] ?><br/>
				Modelo de Tênis: <?= $posted['tenis_modelo'] ?><br/>
				Preço: <?= $posted['preco'] ?> — Quantidade: <?= $posted['quantidade'] ?><br/>
				<br/>
				<strong>Pagamento:</strong><br/>
				Titular do cartão: <?= $posted['card_titular'] ?><br/>
				Número do cartão: <?= $posted['card_num_masked'] ?> (exibido com segurança)<br/>
				Validade: <?= $posted['card_exp_month'] ?>/<?= $posted['card_exp_year'] ?><br/>
				<!-- CVV não é exibido por segurança -->
			</div>
		<?php endif; ?>

		<form method="post" action="">
			<!-- ...existing form fieldsets (Dados do Cliente foram removidos) ... -->

			<fieldset>
				<legend>Dados do Pedido e Seleção do Tênis</legend>

				<div>
					<label>SEXO:</label>
					<span class="inline"><input type="radio" id="sexoM" name="sexo" value="Masculino"><label for="sexoM">Masculino</label></span>
					<span class="inline"><input type="radio" id="sexoF" name="sexo" value="Feminino"><label for="sexoF">Feminino</label></span>
				</div>
				<br/>

				<div>
					<label for="preco">PREÇO:</label>
					<input type="text" id="preco" name="preco" class="small" />
				</div>
					<br/>

				<div>
					<label for="quantidade">QUANTIDADE:</label>
					<input type="text" id="quantidade" name="quantidade" class="small" />
				</div>
					<br/>

				<div>
					<label for="tenis_modelo">Modelo de Tênis:</label>
					<select id="tenis_modelo" name="tenis_modelo">
						<option value="Tênis Esportivo Azul">Tênis Esportivo Azul</option>
						<option value="Tênis Branco Casual">Tênis Branco Casual</option>
						<option value="Tênis Corrida Vermelho">Tênis Corrida Vermelho</option>
						<option value="Tênis Skate Preto">Tênis Skate Preto</option>
						<option value="Outro">Outro</option>
					</select>
				</div>
			</fieldset>

			<!-- ...existing Dados do Cartão / Pagamento fieldset ... -->

			<fieldset>
				<legend>Dados do Cartão / Pagamento</legend>

				<div>
					<label for="card_titular">Titular do Cartão:</label>
					<input type="text" id="card_titular" name="card_titular" class="medium" maxlength="100" />
				</div>
				<br/>

				<div>
					<label for="card_num">Número do Cartão:</label>
					<input type="text" id="card_num" name="card_num" class="medium" maxlength="19" placeholder="#### #### #### ####" />
				</div>
				<br/>

				<div>
					<label for="card_exp_month">Validade:</label>
					<select id="card_exp_month" name="card_exp_month">
						<option value="">MM</option>
						<?php for($m=1;$m<=12;$m++): $mm=str_pad($m,2,'0',STR_PAD_LEFT); ?>
							<option value="<?= $mm ?>"><?= $mm ?></option>
						<?php endfor; ?>
					</select>
					<select id="card_exp_year" name="card_exp_year">
						<option value="">AAAA</option>
						<?php $y = (int)date('Y'); for($i=0;$i<10;$i++): ?>
							<option value="<?= $y+$i ?>"><?= $y+$i ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<br/>

				<div>
					<label for="card_cvv">CVV:</label>
					<input type="password" id="card_cvv" name="card_cvv" class="small" maxlength="4" placeholder="***" />
				</div>
					<br/>
			</fieldset>

			<h3>Confirma Preenchimento do Dados?</h3>
			<div class="actions">
				<input type="submit" value="Enviar formulário" />
				&nbsp;&nbsp;
				<input type="reset" value="Apagar tudo" />
			</div>
		</form>
	</div>
</body>
</html>
