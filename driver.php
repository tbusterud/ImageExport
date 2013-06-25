<?php
require_once('classes/Slurpee.php');
require_once('classes/Utilities.php');
print 'Image Export - 2013.';

/*
 * Format of URL:
 *  %s(1) => limit 51
 *  %s(2) => page
 */

/*
 * This folder must exist with all permissions set prior to use.
 */
$folder = '/Users/trond.busterud/tmp/image/';
//$folder = '/home/developer/tmp/image/';

$url = "http://" . $argv[1] . "/media.json?offset=%s&limit=%s";

try
{
    $limit = 25;
    $offset  = $counter = 0;

    while(true)
    {
        print "\nProcessing offset #{$offset}\tLimit {$limit}";

        $current_url = sprintf($url, $offset, $limit);

        print $current_url;

        $json = \ImageExport\Slurpee::fetchContent($current_url);
        $array = json_decode($json, true);
        var_dump($array['media'][0]);

	/*
	 * Save result
	 */
        $savefolder = $folder . $offset . "/";
	    print "\n$savefolder\n";
        if(!is_dir($savefolder))
        {
            mkdir($savefolder, 0777, true);
        }


        foreach($array['media'] as $media)
        {
            $imgurl = $media['file']['url'];

            $filename = $media['_id'] . "." . $media['scalesTo']['ending'];

            $img = \ImageExport\Slurpee::fetchContent($imgurl);

            \ImageExport\Utilities::persistContent(
                $img,
                $savefolder,
                $filename
            );

        }
        die();
       \ImageExport\Utilities::persistContent(
            $json,
            $savefolder,
            'media.json'
        );

        if($offset >= 50)
        {
            break;
        }
        else
        {
            $counter++;
            $offset += $limit;
        }
    }
}
catch(ErrorException $error)
{
    print $error->getMessage();
    die();
}

print "\nImages slurped.";
