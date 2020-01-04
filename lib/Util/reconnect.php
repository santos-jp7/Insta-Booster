<?php

class reconnect{

	private function relogin(){
		$request = new request();

		if(!$request->reLogin_()){
			self::relogin();
		}
	}

	private function reboot($class){
		print "\n[+] Reiniciando...\n";
		$class->exec();
	}

	function __construct($class){
		
		print "[+] Reconectando...\n";
		
		self::relogin();
		self::reboot($class);
	}
}