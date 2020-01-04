<?php
	
	//BOT
		require(dirname(__FILE__) . '/lib/bot.php');

			//CONEXÕES
				require(dirname(__FILE__) . '/lib/Util/request.php');
				require(dirname(__FILE__) . '/lib/Util/reconnect.php');
				
			//FUNÇÕES
				require(dirname(__FILE__) . '/lib/Util/getUser.php');
				require(dirname(__FILE__) . '/lib/Util/newUser.php');
				require(dirname(__FILE__) . '/lib/Util/newPassword.php');

			//WORKS
				require(dirname(__FILE__) . '/lib/Util/works/principal.php');
				require(dirname(__FILE__) . '/lib/Util/works/explore.php');
				require(dirname(__FILE__) . '/lib/Util/works/tag.php');
				require(dirname(__FILE__) . '/lib/Util/works/booster.php');
				require(dirname(__FILE__) . '/lib/Util/works/teste.php');