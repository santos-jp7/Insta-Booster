<?php

class explore{
	public function exec(){
		$explore = self::getExplore_();
		self::analyze($explore);
	}

	private function loop(){
		sleep(15);
		$explore = self::getExplore_();
		self::analyze($explore);
	}
	
	private function getExplore_(){
		$request = new request();
		$explore = new explore();

		print "[+] Procurando por publicações no explore... \n";

		return $request->explore($explore);
	}

	private function analyze($explore){
		$edges = $explore->data->user->edge_web_discover_media->edges;
		foreach($edges as $value){
			$shortcode = $value->node->shortcode;

			$request = new request();
			$explore = new explore();

			$media = $request->media($explore, $shortcode);

			if($media->data->shortcode_media->viewer_has_liked == false && !is_null($media->data->shortcode_media->viewer_has_liked)){
				print '[+] Curtindo a publicação de '.$media->data->shortcode_media->owner->username."\n";
				self::like($media->data->shortcode_media->id);
				sleep(15);
			}
		}

		self::loop();
	}

	private function like($id){
		$request = new request();
		if(!$request->like($id)){
			print "[-] Ocorreu um erro ao curtir \n";
		}
	}
}