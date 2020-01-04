<?php

	class newPassword{
		public static $password;
		public static $data;
		public static $file;

		public function run(){
			self::$data->password = self::$password;
			$analize = file_put_contents(self::$file, json_encode(self::$data));

			if(!$analize){
				throw new Exception("Ocorreu um erro em salvar sua senha, entre em contato com nossa equipe! \n");
			}else{
				print "[+] Senha salva com sucesso. \n";
			}
		}

		function __construct($password, $data, $file){
			self::$password = $password;
			self::$data = $data;
			self::$file = $file;
		}
	}