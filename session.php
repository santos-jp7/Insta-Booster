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

	$sessionid = $argv[1];

	function c_r($sessionid, $request){
		$ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/whinderssonnunes/?__a=1');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'cookie: sessionid='.$sessionid,
	'referer: https://www.instagram.com/challenge/9683406890/VTvbnXzjAo/',
	'origin: https://www.instagram.com',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'x-csrftoken: X8n6KotHpLK1WtHAnTKu2eTrlZxkDoUY',
	'x-instagram-ajax: 767b10bec02d',
	'x-requested-with: XMLHttpRequest'
		));
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

    		$header .= 'set-cookie: sessionid='.$sessionid.'; Domain=.instagram.com; expires=Wed, 08-Jul-2020 06:30:21 GMT; Max-Age=31449600; Path=/; Secure';
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


	if(is_null($sessionid)){
		echo "[-] Esse comando precisa ser comentado!";
		exit();
	}else{
		main($bot);
		c_r($sessionid, $request);

		$bot->booster();
	}