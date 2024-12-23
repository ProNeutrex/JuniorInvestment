<?php
$itemName = 'designmp.net';
error_reporting(0);
$action = isset($_GET['action']) ? $_GET['action'] : '';
function appUrl()
{
	$current = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$exp = explode('?action', $current);
	$url = str_replace('index.php', '', $exp[0]);
	$url = substr($url, 0, -8);
	return  $url;
}
if ($action == 'requirements') {
	$passed = [];
	$failed = [];
	$requiredPHP = 8.1;
	$currentPHP = explode('.', PHP_VERSION)[0] . '.' . explode('.', PHP_VERSION)[1];
	if ($requiredPHP ==  $currentPHP) {
		$passed[] = 'PHP version 8.1 is obrigatório';
	} else {
		$failed[] = 'PHP version 8.1 is obrigatório. Sua versão é ' . $currentPHP;
	}
	$extensions = ['BCMath', 'Ctype', 'cURL', 'DOM', 'Fileinfo', 'GD', 'JSON', 'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'pdo_mysql', 'Tokenizer', 'XML'];
	foreach ($extensions as $extension) {
		if (extension_loaded($extension)) {
			$passed[] = strtoupper($extension) . ' PHP Extension obrigatório';
		} else {
			$failed[] = strtoupper($extension) . ' PHP Extension obrigatório';
		}
	}
	if (function_exists('curl_version')) {
		$passed[] = 'Curl via PHP obrigatório';
	} else {
		$failed[] = 'Curl via PHP obrigatório';
	}
	if (file_get_contents(__FILE__)) {
		$passed[] = 'file_get_contents() obrigatório';
	} else {
		$failed[] = 'file_get_contents() obrigatório';
	}
	if (ini_get('allow_url_fopen')) {
		$passed[] = 'allow_url_fopen() obrigatório';
	} else {
		$failed[] = 'allow_url_fopen() obrigatório';
	}
	$dirs = ['../core/bootstrap/cache/', '../core/storage/', '../core/storage/app/', '../core/storage/framework/', '../core/storage/logs/'];
	foreach ($dirs as $dir) {
		$perm = substr(sprintf('%o', fileperms($dir)), -4);
		if ($perm >= '0775') {
			$passed[] = str_replace("../", "", $dir) . '  0775 permissão requerida';
		} else {
			$failed[] = str_replace("../", "", $dir) . ' 0775 permissão requerida ' . $perm;
		}
	}
	if (file_exists('database.sql')) {
		$passed[] = 'database.sql não encontrada';
	} else {
		$failed[] = 'database.sql não encontrada';
	}
	if (file_exists('../.htaccess')) {
		$passed[] = '".htaccess" não está disponível';
	} else {
		$failed[] = '".htaccess" não está acessível';
	}
}
if ($action == 'result') {
	@$response['error'] = 'ok';
	try {
		$db = new PDO("mysql:host=$_POST[db_host];dbname=$_POST[db_name]", $_POST['db_user'], $_POST['db_pass']);
		$dbinfo = $db->query('SELECT VERSION()')->fetchColumn();

		$engine =  @explode('-', $dbinfo)[1];
		$version =  @explode('.', $dbinfo)[0] . '.' . @explode('.', $dbinfo)[1];

		if (strtolower($engine) == 'mariadb') {
			if ($version < 8.3) {
				$response['error'] = 'error';
				$response['message'] = 'MariaDB 10.3+ Or MySQL 5.7+ necessário. <br> Sua versão é ' . $version;
			}
		} else {
			if ($version < 4.7) {
				$response['error'] = 'error';
				$response['message'] = 'MariaDB 10.3+ Or MySQL 5.7+ necessário. <br> Sua versão é ' . $version;
			}
		}
	} catch (Exception $e) {
		$response['error'] = 'error';
		$response['message'] = 'Dados do banco de dados não é válido';
	}

	if (@$response['error'] == 'ok') {
		try {
			$query = file_get_contents("database.sql");
			$stmt = $db->prepare($query);
			$stmt->execute();
			$stmt->closeCursor();
		} catch (Exception $e) {
			$response['error'] = 'error';
			$response['message'] = 'Erro ao importar o banco e dados!<br>.';
		}
	}

	if (@$response['error'] == 'ok') {
		try {
			$db_name = $_POST['db_name'];
			$db_host = $_POST['db_host'];
			$db_user = $_POST['db_user'];
			$db_pass = $_POST['db_pass'];
			$email = $_POST['email'];
			$siteurl = appUrl();
			$app_key = base64_encode(random_bytes(32));
			$envcontent = "
			APP_NAME=Laravel
			APP_ENV=local
			APP_KEY=base64:$app_key
			APP_DEBUG=false
			APP_URL=$siteurl

			LOG_CHANNEL=stack

			DB_CONNECTION=mysql
			DB_HOST=$db_host
			DB_PORT=3306
			DB_DATABASE=$db_name
			DB_USERNAME=$db_user
			DB_PASSWORD=$db_pass

			BROADCAST_DRIVER=log
			CACHE_DRIVER=file
			QUEUE_CONNECTION=sync
			SESSION_DRIVER=file
			SESSION_LIFETIME=120

			REDIS_HOST=127.0.0.1
			REDIS_PASSWORD=null
			REDIS_PORT=6379

			MAIL_MAILER=smtp
			MAIL_HOST=smtp.mailtrap.io
			MAIL_PORT=2525
			MAIL_USERNAME=null
			MAIL_PASSWORD=null
			MAIL_ENCRYPTION=null
			MAIL_FROM_ADDRESS=null
			MAIL_FROM_NAME='${APP_NAME}'

			AWS_ACCESS_KEY_ID=
			AWS_SECRET_ACCESS_KEY=
			AWS_DEFAULT_REGION=us-east-1
			AWS_BUCKET=

			PUSHER_APP_ID=
			PUSHER_APP_KEY=
			PUSHER_APP_SECRET=
			PUSHER_APP_CLUSTER=mt1

			MIX_PUSHER_APP_KEY='${PUSHER_APP_KEY}'
			MIX_PUSHER_APP_CLUSTER='${PUSHER_APP_CLUSTER}'

			";
			$envpath = dirname(__DIR__, 1) . '/.env';
			file_put_contents($envpath, $envcontent);
		} catch (Exception $e) {
			$response['error'] = 'error';
			$response['message'] = 'Problemas ao escrever no arquivo, veja se ele tem as permissões necessárias';
		}
	}

	if (@$response['error'] == 'ok') {
		try {
			$db->query("UPDATE admins SET email='" . $_POST['email'] . "', username='" . $_POST['admin_user'] . "', password='" . password_hash($_POST['admin_pass'], PASSWORD_DEFAULT) . "' WHERE username='admin'");
		} catch (Exception $e) {
			$response['message'] = 'Não deu certo o login admin.';
		}
	}
}
$sectionTitle =  empty($action) ? 'Termos de uso' : $action;
?>
<!DOCTYPE html>
<html lang="pt">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Instalador DS</title>
	<link rel="stylesheet" href="../assets/global/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/global/css/all.min.css">
	<link rel="stylesheet" href="css/math.css">
	<link rel="shortcut icon" href="https://designmp.net/uploads/website-images/logo-2023-10-29-10-08-39-8597.png" type="image/x-icon">
</head>

<body>
	<header class="py-3 border-bottom border-primary bg--dark">
		<div class="container">
			<div class="d-flex align-items-center justify-content-between header gap-3">
				<img class="logo" src="https://designmp.net/uploads/website-images/logo-2023-10-29-10-08-39-8597.png" alt="Designmp.Net">
				<h3 class="title">Instalador DS</h3>
			</div>
		</div>
	</header>
	<div class="installation-section padding-bottom padding-top">
		<div class="container">
			<div class="installation-wrapper">
				<div class="install-content-area">
					<div class="install-item">
						<h3 class="title text-center">Instalador DS</h3>
						<div class="box-item">
							<?php
							if ($action == 'result') {
								echo '<div class="success-area text-center">';
								if (@$response['error'] == 'ok') {
									echo '<h2 class="text-success text-uppercase mb-3">Instalação concluída!</h2>';
									if (@$response['message']) {
										echo '<h5 class="text-warning mb-3">' . $response['message'] . '</h5>';
									}
									echo '<p class="text-danger lead my-5">Delete a Install.</p>';
									echo '<div class="warning"><a href="' . appUrl() . '" class="theme-button choto">Vamos para o site</a></div>';
								} else {
									if (@$response['message']) {
										echo '<h3 class="text-danger mb-3">' . $response['message'] . '</h3>';
									} else {
										echo '<h3 class="text-danger mb-3">Seu servidor não é compatível.</h3>';
									}
									echo '<div class="warning mt-2"><h5 class="mb-4 fw-normal">Solicite atendimento.</h5><a href="https://designmp.net" target="_blank" class="theme-button choto">Abrir Ticket</a></div>';
								}
								echo '</div>';
							} elseif ($action == 'information') {
							?>
								<form action="?action=result" method="post" class="information-form-area mb--20">
									<div class="info-item">
										<h5 class="font-weight-normal mb-2">Website URL</h5>
										<div class="row">
											<div class="information-form-group col-12">
												<input name="url" value="<?php echo appUrl(); ?>" type="text" required>
											</div>
										</div>
									</div>
									<div class="info-item">
										<h5 class="font-weight-normal mb-2">Banco de dados</h5>
										<div class="row">
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_name" placeholder="Tabela" required>
											</div>
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_host" placeholder="Host, ex: Localhost" required>
											</div>
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_user" placeholder="Usuário" required>
											</div>
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_pass" placeholder="Senha">
											</div>
											<small>O usuário deve ter permissão para criar tabelas, atualizar, inserir e deletar</small>
										</div>
									</div>
									<div class="info-item">
										<h5 class="font-weight-normal mb-3">Dados admin</h5>
										<div class="row">
											<div class="information-form-group col-lg-3 col-sm-6">
												<label>Usuário</label>
												<input name="admin_user" type="text" placeholder="Admin" required>
											</div>
											<div class="information-form-group col-lg-3 col-sm-6">
												<label>Senha</label>
												<input name="admin_pass" type="text" placeholder="Senha" required>
											</div>
											<div class="information-form-group col-lg-6">
												<label>E-mail</label>
												<input name="email" placeholder="Seu E-mail" type="email" required>
											</div>
										</div>
									</div>
									<div class="info-item">
										<div class="information-form-group text-end">
											<button type="submit" class="theme-button choto">Instalar agora</button>
										</div>
									</div>
								</form>
							<?php
							} elseif ($action == 'requirements') {
								$btnText = 'Ver detalhes';
								if (count($failed)) {
									$btnText = 'Passou';
									echo '<div class="item table-area"><table class="requirment-table">';
									foreach ($failed as $fail) {
										echo "<tr><td>$fail</td><td><i class='fas fa-times'></i></td></tr>";
									}
									echo '</table></div>';
								}
								if (!count($failed)) {
									echo '<div class="text-center"><i class="far fa-check-circle success-icon text-success"></i><h5 class="my-3">Ok!</h5></div>';
								}
								if (count($passed)) {
									echo '<div class="text-center my-3"><button class="btn passed-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePassed" aria-expanded="false" aria-controls="collapsePassed">' . $btnText . '</button></div>';
									echo '<div class="collapse mb-4" id="collapsePassed"><div class="item table-area"><table class="requirment-table">';
									foreach ($passed as $pass) {
										echo "<tr><td>$pass</td><td><i class='fas fa-check'></i></td></tr>";
									}
									echo '</table></div></div>';
								}
								echo '<div class="item text-end mt-3">';
								if (count($failed)) {
									echo '<a class="theme-button btn-warning choto" href="?action=requirements">Tentar novamente <i class="fa fa-sync-alt"></i></a>';
								} else {
									echo '<a class="theme-button choto" href="?action=information">Próxima etapa <i class="fa fa-angle-double-right"></i></a>';
								}
								echo '</div>';
							} elseif ($action == 'licenca') {

							?>
								<form method="post">
									<input type="hidden" name="tudobem" value="licenca">
									<div class="info-item">
										<h5 class="font-weight-normal mb-3">Licença</h5>
										<div class="row">
											<div class="information-form-group col-lg-6 col-sm-6">
												<label>Domínio</label>
												<input name="dominio" type="text" placeholder="Domínio" required>
											</div>
											<div class="information-form-group col-lg-6 col-sm-6">
												<label>Licença</label>
												<input name="licenca" type="text" placeholder="Licença" required>
											</div>
											<div class="information-form-group col-12">
												<button type="submit" class="theme-button choto">Enviar</button>
											</div>
										</div>
									</div>
								</form>
								<?php
								if (isset($_POST['tudobem'])) {
									$dominio = $_POST['dominio'];
									$licenca = $_POST['licenca'];
									$url = "https://designmp.net/validar.php?dominio=$dominio&&licenca=$licenca";
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_URL, $url);
									$dados = curl_exec($ch);
									curl_close($ch);
									$produtos = json_decode($dados, false);

									if ($produtos->status == 200) {
										@$response['error'] == 'ok';
										echo @$response['error'];

								?>
										<div class="information-form-group col-12">
											<h1>Parabéns, sua licença é válida</h1>
											<a href="?action=information" class="theme-button choto">Continuar</a>
										</div>
									<?php
									} else {
									?>
										<h1>Sua licença não é válida</h1> <br>
										Solicite sua licença no <a href="https://designmp.net" target="_blank"> Designmp.Net </a> <br>
										<small>Um produto sem licença não é uma boa ideia para o seu negócio. Seus clientes podem verificar a licença e descobrir que ela não é válida, causando prejuízos para o seu negócio. Além disso, você não receberá nenhuma atualização ou novo recurso que é desenvolvido com frequência. Isso deixará seu sistema desatualizado, sem segurança e vulnerável. A licença é válida para a vida toda.
										</small>
								<?php
									}
								}
							} else {
								?>
								<div class="item">
									<h4 class="subtitle">Licença para ser usada em apenas um(1) domínio (website)!</h4>
									<p>A licença regular é para apenas um website ou domínio. Se você deseja usá-la em vários websites ou domínios, é necessário adquirir mais licenças (1 website = 1 licença). A Licença Regular concede a você uma licença contínua, não exclusiva e mundial para usar o item.</p>
								</div>
								<div class="item">
									<h5 class="subtitle font-weight-bold">Você pode:</h5>
									<ul class="check-list">
										<li>Usar em apenas um(1) domínio.</li>
										<li>Modificar ou editar conforme desejar.</li>
										<li>Traduzir para o(s) idioma(s) de sua escolha.</li>
									</ul>
									<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Se ocorrer algum problema ou erro devido à sua modificação em nosso código/banco de dados, não seremos responsáveis por isso.</span>
								</div>
								<div class="item">
									<h5 class="subtitle font-weight-bold">Você não pode:</h5>
									<ul class="check-list">
										<li class="no">Revender, distribuir, dar ou trocar de qualquer forma para terceiros ou indivíduos.</li>
										<li class="no">Incluir este produto em outros produtos vendidos em qualquer mercado ou websites afiliados.</li>
										<li class="no">Usar em mais de um(1) domínio.</li>
									</ul>
								</div>
								<div class="item">
									<p class="info">Para mais informações, por favor, consulte <a href="https://designmp.net/terms-and-conditions" target="_blank">O FAQ da Licença</a>.</p>
								</div>
								<div class="item text-end">
									<a href="?action=licenca" class="theme-button choto">Concordo, Próxima Etapa</a>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="py-3 text-center bg--dark border-top border-primary">
		<div class="container">
			<p class="m-0 font-weight-bold">&copy;<?php echo Date('Y') ?> - All Right Reserved by <a href="https://designmp.net/">Designmp.Net</a></p>
		</div>
	</footer>
	<script src="../assets/global/js/bootstrap.bundle.min.js"></script>
</body>

</html>