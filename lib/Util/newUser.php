<?php

	class newUser{
		public static $user;
		public static $data;
		public static $file;

		public function run(){
			self::$data->user = self::$user;
			$analize = file_put_contents(self::$file, json_encode(self::$data));

			if(!$analize){
				throw new Exception("Ocorreu um erro em salvar o seu nome de usúario, entre em contato com nossa equipe! \n");
			}else{
				print "[+] Usúario salvo com sucesso. \n";
			}
		}

		function __construct($user, $data, $file){
			self::$user = $user;
			self::$data = $data;
			self::$file = $file;
		}
	}