<?php

class tag{
	public static $varTag;

	public function exec(){
		$json = self::getMedia_();
		self::analyze($json);
	}

	private function loop(){
		sleep(60);
		$json = self::getMedia_();
		self::analyze($json);
	}

	private function getMedia_(){
		$request = new request();
		$tag = new tag();

		print '[+] Procurando por publicações em #'.self::$varTag.'...'."\n";

		return $request->tag($tag, self::$varTag);
	}

	private function analyze($json){
		$request = new request();
		$tag = new tag();
		$edges = $json->data->hashtag->edge_hashtag_to_media->edges;

		foreach($edges as $value){
			$shortcode = $value->node->shortcode;
			$media = $request->media($tag, $shortcode);

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
			print "[-] Ocorreu um erro ao curtir\n";
		}
	}

	public function config($tag){
		self::$varTag = $tag;
	}
}