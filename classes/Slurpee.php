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

    /**
     * Fetch using signing of message
     * @param $url
     * @param $offset
     * @param $limit
     * @return mixed
     */
    public static function fetchWithSignin($url, $offset, $limit)
    {
        $config = parse_ini_file(__DIR__ . '/../config.ini', true);

        $apikey = $config['apikey'];
        $username = $config['username'];
        $usernameheader = $config['usernameheader'];
        $signatureheader = $config['signatureheader'];


        $signature = self::generateSignature(array(
            'limit' => $limit,
            'offset' => $offset
        ), $apikey);

        $curl = curl_init();

        $headers = array(
                "{$usernameheader}: {$username}",
                "{$signatureheader}: $signature"
        );

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        // curl_setopt($curl, CURLOPT_VERBOSE, 1);
        // curl_setopt($curl, CURLOPT_HEADER, 1);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * Generate signature. Coopied from http://docs.keymedia.apiary.io/
     * @param $payload
     * @param $apiKey
     * @return string
     */
    protected static function generateSignature($payload, $apiKey) {
        $message = '';
        foreach ($payload as $key => $value) {
            if (!is_array($value) && substr($v,0,1) !== '@') {
                $message .= $key . $value;
            }
        }

        return hash_hmac("sha1", $message, $apiKey);
    }
}


?>
