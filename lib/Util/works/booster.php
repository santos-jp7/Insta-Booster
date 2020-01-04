<?php

class booster{
	private static $follow = array();

	public function exec(){
		$feed = self::getFeed_();
		self::analyzeFeed_($feed);
	}

	private function loop(){
		$famoso = self::getFamoso_();
		$chainig = self::getDataPerfil_($famoso);

		print "\n";

		self::analyzeFollow_($chainig);

		sleep(15);

		print "\n";

		$i1 = 1;
		while($i1 <= 2){
			print "[+] Intervalo de 3 minutos... $i1/2 \n";
			sleep(180);
			
			$feed = self::getFeed_();
			self::analyzeFeed2_($feed);

			$i1++;
		}

		print "\n";

		self::loopUnfollow_();

		print "\n";

		print "[+] Está seguro encerrar!\n";

		print "\n";

		self::likeFollow_();

		print "\n";

		$i2 = 1;
		while($i2 <= 3){
			print "[+] Intervalo de 3 minutos... $i2/3 \n";
			sleep(180);
			
			$feed = self::getFeed_();
			self::analyzeFeed2_($feed);

			$i2++;
		}

		print "\n";

		$i1 = 0;
		$i2 = 0;
		$feed = self::getFeed_();
		self::analyzeFeed_($feed);
	}

	private function getDataPerfil_($id){
		$request = new request();
		$booster = new booster();

		return $request->getDataPerfil_($booster, $id);
	}

	private function getFeed_(){

		$request = new request();
		$booster = new booster();

		print "[+] Procurando por publicações... \n";

		return $request->feed($booster);
	}

	private function analyzeFollow_($json){
		$edges = $json->data->user->edge_chaining->edges;

		$i = 0;
		while($i <= 13){
			$id = $edges[$i]->node->id;
			$username = $edges[$i]->node->username;

			if(!is_null($id) && !is_null($username)){
				print "[+] Seguindo $username\n";
				self::follow($id, $username);
				self::$follow[] = $id.":".$username;
				sleep(3);
			}

			$i++;
		}
	}

	private function loopUnfollow_(){
		$i = 0;

		foreach(self::$follow as $value){
			if($i > 14){			
				$feed = self::getFeed_();
				self::analyzeFeed2_($feed);

				print "[+] Intervalo de 3 minutos... \n";
				sleep(180);
				$i = 0;
			}			
			$id = explode(":", $value)[0];
			$username = explode(":", $value)[1];

			print "[+] Deixando de seguir $username\n";
			self::unfollow($id, $username);

			sleep(5);
			$i++;
		}

		$i = 0;
		self::$follow = null;
	}

	private function analyzeFeed_($feed){
		$edges = $feed->data->user->edge_web_feed_timeline->edges;
		foreach($edges as $value){
			if($value->node->viewer_has_liked == false && !is_null($value->node->viewer_has_liked)){
				print '[+] Curtindo a publicação de '.$value->node->owner->username."\n";
				self::like($value->node->id);
				sleep(5);
			}
		}
		
		self::loop();
		
	}

	private function analyzeFeed2_($feed){
		$edges = $feed->data->user->edge_web_feed_timeline->edges;
		foreach($edges as $value){
			if($value->node->viewer_has_liked == false && !is_null($value->node->viewer_has_liked)){
				print '[+] Curtindo a publicação de '.$value->node->owner->username."\n";
				self::like($value->node->id);
				sleep(5);
			}
		}	
	}

	private function follow($id, $username){
		$request = new request();
		if(!$request->follow($id)){
			print "[-] Ocorreu um erro em seguir $username.\n";
			print "[-] Tentando novamente em 2 minutos.\n";

			sleep(120);
			print "[+] Seguindo $username\n";
			self::follow($id, $username);
			
		}
	}

	private function unfollow($id, $username){
		$request = new request();
		if(!$request->unfollow($id)){
			print "[-] Ocorreu um erro em deixar de seguir $username.\n";
			print "[-] Tentando novamente em 2 minutos.\n";
			sleep(120);
			
			print "[+] Deixando de seguir $username\n";
			self::unfollow($id, $username);
		}
	}

	private function like($id){
		$request = new request();
		if(!$request->like($id)){
			print "[-] Ocorreu um erro ao curtir \n";
		}
	}

	private function likeFollow_(){
		$request = new request();
		$booster = new booster();

		$notification = $request->notification($booster);
		$relationships = $notification->data->user->edge_activity_count->edges[0]->node->relationships;

		if($relationships > 0){
			if($relationships == 1){
				print "\n[+] 1 novo seguidor!\n";
			}else{
				print "\n[+] $relationships novos seguidores!\n";
			}

			$accountsActivity = $request->accountsActivity_($booster);
			$edges = $accountsActivity->graphql->user->activity_feed->edge_web_activity_feed->edges;

			foreach($edges as $value){
				$followed_by_viewer = $value->node->user->followed_by_viewer;
				$id = $value->node->user->id;
				$username = $value->node->user->username;

				if($followed_by_viewer == false && !is_null($followed_by_viewer)){
					$perfil = $request->perfil($booster, $username);
					$count = $perfil->graphql->user->edge_owner_to_timeline_media->count;

					if($count > 0){
						$edgeperfil = $perfil->graphql->user->edge_owner_to_timeline_media->edges;

						foreach($edgeperfil as $value){
							$shortcode = $value->node->shortcode;

							$media = $request->media($booster, $shortcode);

							if($media->data->shortcode_media->viewer_has_liked == false && !is_null($media->data->shortcode_media->viewer_has_liked)){

								print "[+] Curtindo a publicação de $username\n";
								self::like($media->data->shortcode_media->id);
								sleep(5);
							}
						}
					}
				}
			}
		}		
	}

	private function getFamoso_(){
		$stars = "284920884";
		$num = rand(0, 7);
		return $stars;
	}

}