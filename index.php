<?php
error_reporting(-1);
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
// Base URI is used with relative requests
//    'base_uri' => 'https://stib-mivb-api.herokuapp.com',
    'base_uri' => 'https://stib-mivb-api.herokuapp.com',
    'headers'  => ['content-type' => 'text/xml'],
// You can set any number of default request options.
    'timeout'  => 2.0,
]);

//$response = $client->request('GET', 'getwaitingtimes.php' ['query' => ['1' => '1','line'=>'71']]
//);

//echo '<pre>';print_r($body);print '</pre>';


class Stib
{
    function GetLine($client, $line)
    {
        $request = 'network/line/'.$line;
        $response = $client->request('GET', $request);

        $body = $response->getBody();
        $contents = (string) $body;
        return $contents;

    }
    function GetDirection($client, $line, $Direction)
    {
        $request = "network/line/$line/$Direction";
        $response = $client->request('GET', $request);
        $body = $response->getBody();
        $contents = (string) $body;
        return $contents;
    }
    function GetStop($client, $Stop)
    {
        $request = 'realtime/stop/'.$Stop;
        $response = $client->request('GET', $request);
        $body = $response->getBody();
        $contents = (string) $body;
        return $contents;
    }
}

$Stib = new Stib();

//$direction = $Stib->GetLine($client, 92);
//$direction = json_decode($direction, true);
//echo '<pre>';print_r($direction);print '</pre>';
//$returned = "http://stib-mivb-api.herokuapp.com/network/line/92/1";
//$direction = str_replace("http://stib-mivb-api.herokuapp.com/", "", $returned);
$stops = $Stib->GetDirection($client, "92", "1");
$stops = json_decode($stops, true);
echo '<pre>';print_r($stops);print '</pre>';

//$schedule = $Stib->GetStop($client, 6423);
//$schedule = json_decode($schedule, true);
//echo '<pre>';print_r($schedule);print '</pre>';

?>