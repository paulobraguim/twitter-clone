<?php

	class Tweet{

		private $id;
		private $tweet;		

		function setId($id){
			$this->id = $id;
		}

		function getId() {
	        return $this->id;
	    }

	    function setTweet($tweet){
			$this->tweet = $tweet;
		}

		function getTweet() {
	        return $this->tweet;
	    }
	}	

?>