<?php

namespace App;
use App\User;

class Helper {

	//function to make hyperlinks from urls
	public static function formatUrlsInText($text) {
		return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a target="_blank" href="$1">$1</a>', $text);
	}

	public static function returnHistory($object) {
		if ($object['updated_at'] > $object['created_at']) {
			$lastDate = $object['updated_at'];
		} else {
			$lastDate = $object['created_at'];
		}
		
		$user = User::find($object['created_by']);
		
		if ($user) {
			return "Last updated at " . date('d F Y', strtotime($lastDate)) . " by " . $user->firstname . " " . $user->lastname;
		} else {
			return "Last updated at " . date('d F Y', strtotime($lastDate));			
		}
	}
}

?>