
<?php
	
class request{
	private static $varUser;
	private static $varPassword;
	public static $varUserId;
	public static $varFr;
	public static $varUsername;

	public static $varTag;
	public static $varComent;

	public static $varCookie;

	public function login(){

		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/accounts/login/ajax/');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'origin: https://www.instagram.com',
	'referer: https://www.instagram.com/accounts/login/?source=auth_switcher',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'x-csrftoken: X8n6KotHpLK1WtHAnTKu2eTrlZxkDoUY',
	'x-instagram-ajax: 767b10bec02d',
	'x-requested-with: XMLHttpRequest'
		));
	    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username='.self::$varUser.'&password='.self::$varPassword.'&queryParams=%7B%22source%22%3A%22auth_switcher%22%7D');
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
    		throw new Exception("Verifique sua conexão com a internet e tente novamente.");
    	}elseif($code != 200){
    		throw new Exception("Ocorreu um erro em efetuar login. Entre em contato com nossa equipe! [http]");	
    	}elseif($json->status != "ok"){
    		throw new Exception("Ocorreu um erro em efetuar login. Entre em contato com nossa equipe! [status]");
    	}elseif($json->user == false){
    		echo $body;
    		throw new Exception("Verifique seu nome de usuário.");
    	}elseif($json->authenticated == false){
    		throw new Exception("Verifique sua senha.");
    	}elseif($json->authenticated == true){
    		self::$varUserId = $json->userId;
    		self::$varFr = $json->fr;

    		self::cookie($header);

    		return true;
    	}
	}

	public function reLogin_(){

		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/accounts/login/ajax/');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'origin: https://www.instagram.com',
	'referer: https://www.instagram.com/accounts/login/?source=auth_switcher',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'x-csrftoken: X8n6KotHpLK1WtHAnTKu2eTrlZxkDoUY',
	'x-instagram-ajax: 767b10bec02d',
	'x-requested-with: XMLHttpRequest'
		));
	    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username='.self::$varUser.'&password='.self::$varPassword.'&queryParams=%7B%22source%22%3A%22auth_switcher%22%7D');
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
    		return false;
    	}elseif($code != 200){
    		return false;	
    	}elseif($json->status != "ok"){
    		return false;
    	}elseif($json->user == false){
    		echo "USER: ".$body;
    		throw new Exception("Verifique seu nome de usuário.");
    	}elseif($json->authenticated == false){
    		throw new Exception("Verifique sua senha.");
    	}elseif($json->authenticated == true){
    		self::$varUserId = $json->userId;
    		self::$varFr = $json->fr;

    		self::cookie($header);

    		return true;
    	}
	}

	public function username(){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/graphql/query/?query_hash=7c16654f22c819fb63d1183034a5162f&variables=".urlencode('{"user_id":"'.self::$varCookie["ds_user_id"].'","include_chaining":false,"include_reel":true,"include_suggested_users":false,"include_logged_out_extras":false,"include_highlight_reels":false}'));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

        flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		throw new Exception("Ocorreu um erro em salvar seu username. Entre em contato com nossa equipe! [http]");
    	}elseif($json->status != "ok"){
    		throw new Exception("Ocorreu um erro em salvar seu username. Entre em contato com nossa equipe! [status]");
    	}else{
    		self::$varUsername = $json->data->user->reel->user->username;
    	}	
	}

	public function feed($class){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/graphql/query/?query_hash=01b3ccff4136c4adf5e67e1dd7eab68d");
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar seu feed. [http $code].\n";
			$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar seu feed. Reiniciando... \n";
    		sleep(15);
			$class->exec();
    	}else{
    		return $json;
    	}
	}

	public function nextFeed_($class, $hash){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/graphql/query/?query_hash=01b3ccff4136c4adf5e67e1dd7eab68d&variables=".urlencode('{"fetch_media_item_count":12,"fetch_media_item_cursor":"'.$hash.'","has_stories":false}'));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar seu feed. [http $code].\n";
			$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar seu feed. Reiniciando... \n";
    		sleep(15);
    		$class->exec();
    	}else{
    		return $json;
    	}
	}

	public function like($id){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/web/likes/'.$id.'/like/');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    
	    curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'cookie: sessionid='.self::$varCookie["sessionid"],
	'origin: https://www.instagram.com',
	'referer: https://www.instagram.com/',
	'user-agent: Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.119 Mobile Safari/537.36',
	'x-csrftoken: Mq01OabZa6svnyVp9xL44xgRglZjoObc',
	'x-ig-app-id: 936619743392459',
	'x-instagram-ajax: b677048ec023',
	'x-requested-with: XMLHttpRequest'
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];;

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		return false;
    	}elseif($json->status != "ok"){
    		return false;
    	}else{
    		return true;
    	}	
	}

	public function coment($id){

	}

	public function cookie($str){
		self::$varCookie["sessionid"] = trim(explode(";", explode("set-cookie: sessionid=", $str)[1])[0]);
		self::$varCookie["ds_user_id"] = trim(explode(";", explode("set-cookie: ds_user_id=", $str)[1])[0]);
		self::$varCookie["mid"] = trim(explode(";", explode("set-cookie: mid=", $str)[1])[0]);
	}

	public function getUserInfo_(){
		$user["id"] = self::$varUserId;
		$user["username"] = self::$varUsername;

		return $user;
	}

	public function explore($class){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/graphql/query/?query_hash=ecd67af449fb6edab7c69a205413bfa7&variables=%7B%22first%22%3A45%7D");
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar seu explore. [http $code].\n";
    		$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar seu explore. Reiniciando... \n";
    		sleep(15);
    		$explore = new explore();
			$explore->exec();
    	}else{
    		return $json;
    	}
	}

	public function media($class, $shortcode){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/graphql/query/?query_hash=49699cdb479dd5664863d4b647ada1f7&variables='.urlencode('{"shortcode":"'.$shortcode.'","child_comment_count":3,"fetch_comment_count":40,"parent_comment_count":24,"has_threaded_comments":false}'));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code == 429){
    		print "[-] Ouve um bloqueio do servidor, reiniciando... [http: $code].\n";
    		$class->exec();
    	}elseif($code != 200){
    		print "[-] Ocorreu um erro em acessar a publicação. [http: $code].\n";
    		$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar a publicação. Reiniciando... \n";
    		sleep(15);
    		$class->exec();
    	}else{
    		return $json;
    	}		
	}

	public function tag($class, $tag){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/graphql/query/?query_hash=f92f56d47dc7a55b606908374b43a314&variables='.urlencode('{"tag_name":"'.$tag.'","show_ranked":false,"first":5}'));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar a tag. [http $code].\n";
    		$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar a tag. Reiniciando... \n";
    		sleep(15);
    		$class->exec();
    	}else{
    		return $json;
    	}		
	}

	public function perfil($class, $username){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/'.$username.'/?__a=1');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar o perfil. [http $code].\n";
    		$reconnect = new reconnect($class);
    	}else{
    		return $json;
    	}
	}

	public function getDataPerfil_($class, $id){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/graphql/query/?query_hash=7c16654f22c819fb63d1183034a5162f&variables=".urlencode('{"user_id":"'.$id.'","include_chaining":true,"include_reel":true,"include_suggested_users":false,"include_logged_out_extras":false,"include_highlight_reels":true}'));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar o perfil. [http $code].\n";
    		$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar o perfil. Reiniciando... \n";
    		sleep(15);
    		$class->exec();
    	}else{
    		return $json;
    	}	
	}

	public function follow($id){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/web/friendships/'.$id.'/follow/?hl=pt-br');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-length: 0',
	'content-type: application/x-www-form-urlencoded',
	'cookie: sessionid='.self::$varCookie["sessionid"],
	'origin: https://www.instagram.com',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'x-csrftoken: 6ABeGrKzkvdma2ouKpYuSfi1jHUHHnNa',
	'x-instagram-ajax: 715dcf29ace5'
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		return false;
    	}elseif($json->status != "ok"){
    		return false;
    	}else{
    		return true;
    	}
	}

	public function unfollow($id){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/web/friendships/'.$id.'/unfollow/?hl=pt-br');
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-length: 0',
	'content-type: application/x-www-form-urlencoded',
	'cookie: sessionid='.self::$varCookie["sessionid"],
	'origin: https://www.instagram.com',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'x-csrftoken: 6ABeGrKzkvdma2ouKpYuSfi1jHUHHnNa',
	'x-instagram-ajax: 715dcf29ace5'
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		return false;
    	}elseif($json->status != "ok"){
    		return false;
    	}else{
    		return true;
    	}
	}

	public function notification($class){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/graphql/query/?query_hash=0f318e8cfff9cc9ef09f88479ff571fb&variables=".urlencode('{"id":"'.self::$varUserId.'"}'));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro em acessar suas notificações. [http $code].\n";
    		$reconnect = new reconnect($class);
    	}elseif($json->status != "ok"){
    		print "[-] Ocorreu um erro em acessar suas notificações. Reiniciando... \n";
    		sleep(15);
    		$class->exec();
    	}else{
    		return $json;
    	}
	}

	public function accountsActivity_($class){
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/accounts/activity/?__a=1&include_reel=true");
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
	'cookie: sessionid='.self::$varCookie["sessionid"]
		));
	    $exec = curl_exec($ch);
	    $json = json_decode($exec);
    	$status = curl_getinfo($ch);
    	$code = $status['http_code'];

    	flush();
    	ob_flush();
    	curl_close($ch);

    	if($code != 200){
    		print "[-] Ocorreu um erro (accountsActivity). [http $code].\n";
    		$reconnect = new reconnect($class);
    	}else{
    		//print $exec;
    		return $json;
    	}
	}

	public function config($user='', $password=''){
			self::$varUser = $user;
			self::$varPassword = $password;
	}
}