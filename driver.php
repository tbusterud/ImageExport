<?php

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

        $content     = \MWFeedExporter\ExportUtilities::fetchResourceContent($current_url);
        if(strlen($content) == 51)
        {
            print "\nEmpty content";
            break;
        }
        $converted   = \MWFeedExporter\XMLExportUtilities::transformXML(
                            $content,
                            '/Volumes/projects/git/MWFeedExporter/xslt/EzToWin.xsl'
                        );
        $cleansed    = \MWFeedExporter\XMLExportUtilities::stripXMLDeclaration($converted);
        \MWFeedExporter\ExportUtilities::persistContent(
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

try
{
    print "\nConcatinating content ...";
    \MWFeedExporter\ExportUtilities::concatFilesInFolder('/Users/trond.busterud/tmp/export/', 'concatinated.xml');

    print "\nPatching exported XML";
    $xml_start_tags = <<<STARTTAGS
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <recipes>
STARTTAGS;

    $xml_end_tags = <<<ENDTAGS
    </recipes>
</response>
ENDTAGS;

    \MWFeedExporter\XMLExportUtilities::patchXMLStructure(
            '/Users/trond.busterud/tmp/export/concatinated.xml',
            '/Users/trond.busterud/tmp/export/exported_feed.xml',
            $xml_start_tags, $xml_end_tags
        );
}
catch(Exception $error)
{
    print $error->getMessage();
    die();
}
print "\nExport finished.";