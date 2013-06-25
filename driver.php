<?php
require_once('classes/slurpee.php');

print 'Image Export - 2013.';

/*
 * Format of URL:
 *  %s(1) => limit 51
 *  %s(2) => page
 */

$folder = '/Users/trond.busterud/tmp/image/';

$url = "http://" + $argv[1] + "media.json?offset=%s&limit=%S";

//try
//{
    $limit = 25;
    $offset  = $counter = 0;

    while(true)
    {
        print "\nProcessing offset #{$offset}\tLimit {$limit}";

        $current_url = sprintf($url, $offset, $limit);

        print $current_url;

        $json = Slurpee::fetchJSONIndex($url);
        $array = json_decode($json, true);
        var_dump($array['media'][0]);

        $savefolder = $folder + $offset + '/';

        if(!is_dir($savefolder))
        {
            mkdir($savefolder, 0700, true);
        }


        ExportUtilities::persistContent(
            $cleansed,
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
/*}
catch(ErrorException $error)
{
    print $error->getMessage();
    die();
}
*/
print "\nImages slurped.";