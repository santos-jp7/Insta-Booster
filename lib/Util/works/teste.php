<?php

class teste{
	
}

class teste3{
	private static $a = array();

	public function exec(){
		print "init\n";
		print 'count: '.count(self::$a)."\n";

		self::$a[] = "a";
		self::$a[] = "b";

		var_dump(self::$a);

		self::$a = null;

		self::$a[] = "c";

		sleep(5);


		/* 
		print "\nrecconect\n";
		$class = new teste();
		$reconnect = new reconnect($class);
		*/


	}
}


class teste2{
	public function exec(){
		$request = new request();
		$teste = new teste();

		$notification = $request->notification($teste);
		//$relationships = $notification->data->user->edge_activity_count->edges[0]->node->relationships;
		$relationships = 2;

		print "\nrelationships: $relationships\n";

		if($relationships > 0){
			if($relationships == 1){
				print "\n1 novo seguidor!\n";
			}else{
				print "\n$relationships novos seguidores!\n";
			}

			$accountsActivity = $request->accountsActivity_($teste);
			$edges = $accountsActivity->graphql->user->activity_feed->edge_web_activity_feed->edges;


			$i = 0;
			while($relationships > $i){
				$followed_by_viewer = $edges[$i]->node->user->followed_by_viewer;
				$id = $edges[$i]->node->user->id;
				$username = $edges[$i]->node->user->username;

				if($followed_by_viewer == false && !is_null($followed_by_viewer)){
					$perfil = $request->perfil($username);
					$count = $perfil->graphql->user->edge_owner_to_timeline_media->count;

					if($count > 0){
						$edgeperfil = $perfil->graphql->user->edge_owner_to_timeline_media->edges;

						print "\nCurtindo publicações de $username\n";
						foreach($edgeperfil as $value){
							$shortcode = $value->node->shortcode;

							$media = $request->media($teste, $shortcode);

							if($media->data->shortcode_media->viewer_has_liked == false && !is_null($media->data->shortcode_media->viewer_has_liked)){

								self::like($media->data->shortcode_media->id);
								sleep(15);
							}
						}
					}
				}

				$i++;
			}
		}
	}

	private function like($id){
		$request = new request();
		if(!$request->like($id)){
		print "[-] Ocorreu um erro ao curtir \n";
		}
	}
}