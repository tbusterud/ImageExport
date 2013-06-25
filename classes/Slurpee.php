<?php

namespace ImageExport;

class Slurpee
{
	public static function fetchContent($url = null)
	{
		if(empty($url) === false)
		{
			$content = file_get_contents($url);
			return $content;
		}
		else
		{
			throw new Exception('JSON source URL is empty!');
		}
	}
}


?>
