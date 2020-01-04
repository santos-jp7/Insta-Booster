<?php
	error_reporting(1);
	ini_set('memory_limit', '-1');
	require_once 'init.php';
	try{
		$varData = json_decode(file_get_contents(dirname(__FILE__) . "/user.json"));	
		$varFile = dirname(__FILE__) . "/user.json";

		$bot = new bot($varData, $varFile);

		$user = $varData->user;
		$password = $varData->password;

		$request = new request();
		$request->config($user, $password);
	}catch (Exception $e){
		print $e->getMessage();
	}

	$token = $argv[1];
	$fb_id = $argv[2];

	function c_r($token, $fb_id, $request){
		$ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/accounts/login/ajax/facebook/');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'origin: https://www.instagram.com',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36',
	'x-csrftoken: X8n6KotHpLK1WtHAnTKu2eTrlZxkDoUY',
	'x-instagram-ajax: 767b10bec02d',
	'x-requested-with: XMLHttpRequest'
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'accessToken='.$token.'&fbUserId='.$fb_id);

	    $exec = curl_exec($ch);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($exec, 0, $header_size);
		$body = substr($exec, $header_size);

	    $json = json_decode($body);

        flush();
    	ob_flush();
    	curl_close($ch);

    	if($code == 0){
    		echo "[-] Verifique sua conexão com a internet e tente novamente.";
    		exit();
    	}
    		echo "BODY: $body";
    		$request->cookie($header);
	}

	function main($bot){
		try{
			print "[.] Loading... \n";
			$bot->getUser_();
		}catch (Exception $e) {
			print $e->getMessage();
		}		
	}


	if(is_null($token) OR is_null($fb_id)){
		echo "[-] Esse comando precisa ser comentado!";
		exit();
	}else{
		main($bot);
		c_r($token, $fb_id, $request);

		$bot->default();
	}