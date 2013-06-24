<?php

class Slurpee
{
	public static function fetchJSONIndex($url = null)
	{
		if(empty($url) === false)
		{
			$json = file_get_contents($url);
			return $json;
		}
		else
		{
			throw new Exception('JSON source URL is empty!');
		}
	}
}


?>
