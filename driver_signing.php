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
// $folder = '/Users/trond.busterud/tmp/image/';
// $folder = '/home/developer/tmp/image/';

$url = "http://" . $argv[1] . "/media.json?offset=%s&limit=%s";

$errorfile = "Images with wrong size:\n";

try
{
    $limit = 25;
    $offset  = $counter = 0;

    while(true)
    {
        print "\nProcessing offset #{$offset}\tLimit {$limit} ";

        $current_url = sprintf($url, $offset, $limit);

        print $current_url;

        $json = \ImageExport\Slurpee::fetchWithSignin($current_url, $offset, $limit);
        $array = json_decode($json, true);

        if($array['media'] == null)
        {
            break;
        }

	    /*
	    * Save result
	    */
        $savefolder = $folder . $offset . "/";

        if(!is_dir($savefolder))
        {
            mkdir($savefolder, 0777, true);
        }


        foreach($array['media'] as $media)
        {
            $imgurl = $media['file']['url'];

            $filename = $media['_id'] . "." . $media['scalesTo']['ending'];

            $img = \ImageExport\Slurpee::fetchWithSignin($imgurl, $offset, $limit);

            \ImageExport\Utilities::persistContent(
                $img,
                $savefolder,
                $filename
            );

            $filesize = filesize($savefolder.$filename);

            if($filesize != $media['file']['size'])
            {
                print $media['file']['size'] . "\n";
                print "\nError: {$filename} incorrect size";
            }

            $errorfile .= "\n{$filename} Offset: {$offset}";

        }

       \ImageExport\Utilities::persistContent(
            $json,
            $savefolder,
            'media.json'
        );

        if($offset >= 46650)
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
}

try
{
    \ImageExport\Utilities::persistContent(
        $errorfile,
        $folder,
        'error.txt'
    );
}
catch(ErrorException $error)
{
    print $error->getMessage();
}

print "\nImages slurped.\n";
