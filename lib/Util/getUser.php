<?php
	
	class getUser{
		public static $varData;

		public function Analise(){
			if(is_null(self::$varData)){
				throw new Exception('Arquivo "user.json" está vazio.');
			}elseif(!property_exists(self::$varData, "user")){
				throw new Exception('Ocorreu um erro em encontrar a propriedade "user" em "user.json".');
			}elseif(!property_exists(self::$varData, "password")){
				throw new Exception('Ocorreu um erro em encontrar a propriedade "password" em "user.json".');
			}elseif(strlen(self::$varData->user) == 0){
				throw new Exception('Propriedade "user" em "user.json" está vazia.');
			}elseif(strlen(self::$varData->password) == 0){
				throw new Exception('Propriedade "password" em "user.json" está vazia.');
			}else{
				return true;
			}
		}
		public function User(){
			return self::$varData->user;
		}
		public function Password(){
			return self::$varData->password;
		}
		function __construct($data){
			self::$varData = $data;
		}
	}