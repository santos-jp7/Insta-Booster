<?php

class principal{

	private static $feeds;
	private static $total = 0;

	public function exec(){

		$feed = self::getFeed_();
		self::analyze($feed);
	}

	private function loop($has, $hash=''){
		if(self::$total == 100){
			//print "[+] 100 feeds carregados. \n";
			sleep(15);

			self::$total = 0;

			$feed = self::getFeed_();
			self::analyze($feed);	
		}elseif($has && self::$feeds == 4){
			//print '[+] Total de mémoria alocada: '.memory_get_usage(true)."\n";
			sleep(15);
			print "[+] Verificando atualizações recentes. \n";
			sleep(2);
			
			$feed = self::getFeed_();
			self::analyze2($feed);

			//print "[+] Analisando o feed. \n";
			sleep(15);

			$feed = self::nextFeed_($hash);
			self::$total++;
			self::analyze($feed);			
		}elseif($has && self::$feeds != 4){
			//print "[+] Carregando próxima página do feed. \n";
			sleep(15);

			$feed = self::nextFeed_($hash);
			self::$total++;
			self::analyze($feed);
		}else{
			//print "[+] Todo o feed analisado, verificando recentes... \n";
			self::$total = 0;
			sleep(15);

			$feed = self::getFeed_();
			self::analyze($feed);
		}
	}

	private function getFeed_(){
		self::$feeds = 0;

		$request = new request();
		$principal = new principal();

		print "[+] Procurando por publicações... \n";

		return $request->feed($principal);
	}

	private function nextFeed_($hash){
		self::$feeds++;

		$request = new request();
		$principal = new principal();

		return $request->nextFeed_($principal, $hash);
	}

	private function analyze($feed){
		$edges = $feed->data->user->edge_web_feed_timeline->edges;
		foreach($edges as $value){
			if($value->node->viewer_has_liked == false && !is_null($value->node->viewer_has_liked)){
				print '[+] Curtindo a publicação de '.$value->node->owner->username."\n";
				self::like($value->node->id);
				sleep(7);
			}
		}
		if($feed->data->user->edge_web_feed_timeline->page_info->has_next_page){
			$hash = $feed->data->user->edge_web_feed_timeline->page_info->end_cursor;

			self::loop(true, $hash);
		}else{
			self::loop(false);
		}
	}

	private function analyze2($feed){
		$edges = $feed->data->user->edge_web_feed_timeline->edges;
		foreach($edges as $value){
			if($value->node->viewer_has_liked == false && !is_null($value->node->viewer_has_liked)){
				print '[+] Curtindo a publicação de '.$value->node->owner->username."\n";
				self::like($value->node->id);
				sleep(7);
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