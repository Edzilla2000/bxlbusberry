<?php
error_reporting(-1);

class Stib
{
    function GetLine($client, $line)
    {
        $request = 'network/line/'.$line;
        $response = $client->request('GET', $request);

        $body = $response->getBody();
        $contents = (string) $body;
        $line = json_decode($contents, true);
        $directions = [];
        reset($line['directions']);
        while (list($station, $directionID) = each($line['directions']))
            {
                $pieces = explode("/", $directionID);
                $directionID = end($pieces);
                $directions += array ($station => $directionID);
            }
        $LineObject = (object) [
            'name' => $line['name'],
            'id' => $line['id'],
            'directions' => $directions,
        ];
        return $LineObject;

    }
    function GetDirection($client, $line, $Direction)
    {
        $request = "network/line/$line/$Direction";
        $response = $client->request('GET', $request);
        $body = $response->getBody();
        $line = json_decode($body, true);
        
        $stops = [];
        reset($line['stops']);
        $LineObject = (object) [
            'line' => $line,
            'stops' => $line['stops'],
        ];
        
        return $LineObject;
    }
    function GetStop($client, $Stop, $StopName)
    {
        $request = 'realtime/stop/'.$Stop;
        $response = $client->request('GET', $request);
        $body = $response->getBody();
        $line = json_decode($body, true);
        
        $LineObject = (object) [
            'name' => $StopName,
            'id' => $Stop,
            'results' => $line['results'],
        ];
        
        return $LineObject;
    }
    
    function GetLines($client)
    {
        $request = 'network/lines';
        $response = $client->request('GET', $request);
        $body = $response->getBody();
        $line = json_decode($body, true);
        
        $LineObject = (object) [
            'lines' => $line['lines'],
        ];
        
        return $LineObject;
    }
}
?>