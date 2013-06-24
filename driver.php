<?php
require_once('slurpee.php');

print 'Image Export - 2013.';

/*
 * Format of URL:
 *  %s(1) => limit 51
 *  %s(2) => page
 */

$url = $argv[1];

try
{
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


        ExportUtilities::persistContent(
            $cleansed,
            '/Users/trond.busterud/tmp/export/'
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