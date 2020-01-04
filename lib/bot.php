<?php

class bot{

	private static $varUser;
	private static $varPassword;

	public static $varData;
	public static $varFile;

	public function getUser_(){
		$getUser = new getUser(self::$varData);

		if($getUser->Analise()){
			self::$varUser = $getUser->User();
			self::$varPassword = $getUser->Password();
		}
	}

	public function login(){
		$request = new request();
		$request->config(self::$varUser, self::$varPassword);

		if($request->login()){
			return true;
		}

	}

	public function getUserName_(){
		$request = new request();

		$request->username();

		print '[+] Logado como: '.$request->getUserInfo_()["username"].' (Id: '.$request->getUserInfo_()["id"].').'."\n";
	}

	public function newUser_($user){
		$newUser = new newUser($user, self::$varData, self::$varFile);

		$newUser->run();
	}

	public function newPassword_($password){
		$newPassword = new newPassword($password, self::$varData, self::$varFile);

		$newPassword->run();
	}

	public function default(){
		$principal = new principal();
		$principal->exec();
	}

	public function explore(){
		$explore = new explore();
		$explore->exec();
	}

	public function tag($str){
		$tag = new tag();

		$tag->config($str);
		$tag->exec();
	}

	public function booster(){
		$booster = new booster();

		$booster->exec();
	}

	public function teste(){
		$teste = new teste();

		$teste->exec();
	}

	function __construct($data, $file){
		self::$varData = $data;
		self::$varFile = $file;
	}
}