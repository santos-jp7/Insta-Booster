<?php
	error_reporting(0);
	ini_set('memory_limit', '-1');
	require_once 'init.php';
	try{
		$varData = json_decode(file_get_contents(dirname(__FILE__) . "/user.json"));	
		$varFile = dirname(__FILE__) . "/user.json";

		$bot = new bot($varData, $varFile);
	}catch (Exception $e){
		print $e->getMessage();
	}

	function run($bot, $function, $str=''){
		try{
			print '[+] Bot iniciado.'."\n\n";

			$bot->$function($str);
		}catch (Exception $e){
			print $e->getMessage();
		}
	}

	function main($bot, $function, $str=''){
		try{
			print "[.] Loading... \n";
			$bot->getUser_();
			if($bot->login()){
				$bot->getUserName_();
			}

			run($bot, $function, $str);
		}catch (Exception $e) {
			print $e->getMessage();
		}		
	}

	function user($bot, $user){
		try{
			$bot->newUser_($user);
		}catch (Exception $e){
			print $e->getMessage();
		}
	}

	function password($bot, $password){
		try{
			$bot->newPassword_($password);
		}catch (Exception $e){
			$e->getMessage();
		}
	}

	function tag($bot){
		try{
			print "[-] Função ainda em desenvolvimento, aguarde atualizações!";
		}catch (Exception $e){
			$e->getMessage();
		}
	}

	function coment($bot){
		try{
			print "[-] Função ainda em desenvolvimento, aguarde atualizações!";
		}catch (Exception $e){
			$e->getMessage();
		}
	}

	function help(){
		print 
'Uso:
	php index.php [-h] [-d] [-u user] [-p password] [-t tag] [-c coment]'
."\n".'
Opcões: 
'."\n".'
	-h / --help 		Informações de como utilizar o bot.
	-d / --debug 		Executa o modo de depuração.
				(útil quando ocorrer algum erro e precisar contatar 
				nossa equipe.)
	-u / --user		Cadastra ou atualiza seu nome de usúario de 
				sua conta em nosso banco de dados local.
	-p / --password 	Cadastra ou atualiza sua senha de usúario de
				sua conta em nosso banco de dados local.
	-t / --tag 		Curte automaticamente publicações de uma cer-
				ta tag.
	-c / --coment 		Auto comenta em publicações.
	-e / --explore 		Curte automaticamente publicações do explore.
	'."\n";
}
	
	foreach($argv as $vd){
		if($vd == "-d" OR $vd == "--debug"){
			error_reporting(1);
			print '[.] Debug ativo.'."\n";
		}
	}
	if($argc == 1 OR $argv[1] == "-d" OR $argv[1] == "--debug"){
		main($bot, 'default');
	}elseif($argv[1] == "-u" OR $argv[1] == "--user"){
		if(is_null($argv[2])){
			print '[-] Você precisa definir seu nome de usúario. Exemplo: "php index.php -u fulano123".';
		}else{
			user($bot, $argv[2]);
		}
	}elseif($argv[1] == "-p" OR $argv[1] == "--password"){
		if(is_null($argv[2])){
			print '[-] Você precisa definir sua senha. Exemplo: "php index.php -p 12345678".';
		}else{
			password($bot, $argv[2]);
		}
	}elseif($argv[1] == "-t" OR $argv[1] == "--tag"){
		if(is_null($argv[2]) OR $argv[2] == "-d" OR $argv[2] == "--debug"){
			print '[-] Você precisa definir a tag. Exemplo: "php index.php -t Anita".';
		}else{
			main($bot, 'tag', $argv[2]);
		}
	}elseif($argv[1] == "-c" OR $argv[1] == "--coment"){
		if(is_null($argv[2])){
			print '[-] Você precisa definir seu comentário. Exemplo: "php index.php -c Feliz Natal".';
		}else{
			user($bot, $argv[2]);
		}
	}elseif($argv[1] == "-h" OR $argv[1] == "--help"){
		help();
	}elseif($argv[1] == "-e" OR $argv[1] == "--explore"){
		main($bot, 'explore');
	}elseif($argv[1] == "-b" OR $argv[1] == "--booster"){
		main($bot, 'booster');
	}elseif($argv[1] == "--teste"){
		main($bot, 'teste');
	}else{
		print '[-] Não foi possível reconhecer: "'.$argv[1].'"'."\n".'[-] Utilize: "php index.php -h" para ajuda.';
	}