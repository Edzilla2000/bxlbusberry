<!DOCTYPE xhtml>
<html>
<head>
<!-- Include meta tag to ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- Include jQuery Mobile stylesheets -->
      <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
      <!-- Include the jQuery library -->
      <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
      <!-- Include the jQuery Mobile library -->
      <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
      <title>BXLBusBerry</title>
      </head>

<?php
      error_reporting(-1);
require 'api.inc.php';
require 'vendor/autoload.php';

use GuzzleHttp\Client;


$client = new Client([
    'base_uri' => 'https://stib-mivb-api.herokuapp.com',
    'headers'  => ['content-type' => 'text/xml'],
    'timeout'  => 2.0,
]);


$Stib = new Stib();

//$lines = $Stib->GetLines($client);
//echo '<pre>';print_r($lines);print '</pre>';
//$schedule = $Stib->GetLine($client, 92);
//echo '<pre>';print_r($schedule);print '</pre>';
//$Stops = $Stib->GetDirection($client, $schedule->id, 2);
//echo '<pre>';print_r($Stops);print '</pre>';
//$time = $Stib->GetStop ($client, 6420, "VANDERKINDERE");
//echo '<pre>';print_r($time);print '</pre>';


function PresentLines($Stib, $client)
{
    echo '<table data-role="table" data-mode="columntoggle" class="ui-responsive" id="myTable">
  <thead>
    <tr>';
        echo '<th>LineID</th>';
        echo '<th data-priority="1">LineName</th>';
        echo '<th data-priority="2">LineMode</th>';
        echo '</tr>
    </thead>
    <tbody>';
        $lines = $Stib->GetLines($client);
        foreach ($lines as $key => $value)
            {
                foreach ($value as $line)
                    {
                        echo '<tr>';
                        echo "<td><a href='#' onclick = 'GetLine(".$line['id'].")' id='".$line['id']."' class='".$line['id']."'>".$line['id']."</a></td>";
                        echo "<td>".$line['name']."</td>";
                        echo "<td>".$line['mode']."</td>";
                        echo '</tr>';
                    }
            }

        echo '</tbody>
        </table>';
}
function GetLine($Stib, $client, $line)
{
    echo '<table data-role="table" data-mode="columntoggle" class="ui-responsive" id="myTable">
  <thead>
    <tr>';
        echo '<th>Direction</th>';
        echo '<th data-priority="1">LineDirection</th>';
        echo '<th data-priority="2">LineName</th>';
        echo '</tr>
    </thead>
    <tbody>';
        $currentline = $Stib->GetLine($client, $line);
        foreach ($currentline->directions as $key => $value)
            {
                echo '<tr>';
                echo "<td><a href='#' onclick = 'GetStops(".$currentline->id.", ".$value.")' id='".$currentline->id."' class='".$currentline->id."'>".$value."</a></td>";
                echo "<td>".$key."</td>";
                echo "<td>".$currentline->name."</td>";
                echo '</tr>';
            }

        echo '</tbody>
        </table>';
}
function GetStops($Stib, $client, $line, $direction)
{
    echo '<table data-role="table" data-mode="columntoggle" class="ui-responsive" id="myTable">
  <thead>
    <tr>';
        echo '<th>Stop name</th>';
        echo '<th data-priority="1">Line</th>';
        echo '<th data-priority="2">StopID</th>';
        echo '</tr>
    </thead>
    <tbody>';
        $Stops = $Stib->GetDirection($client, $line, $direction);
//        echo '<pre>';print_r($Stops->line);print '</pre>';
        foreach ($Stops->line['stops'] as $stop)
            {
                echo '<tr>';
                echo "<td><a href='#' onclick = 'GetStop(".$stop['id'].", \"".$stop['name']."\")' id='".$stop['id']."' class='".$stop['id']."'>".$stop['name']."</a></td>";
                echo "<td>".$line."</td>";
                echo "<td>".$stop['id']."</td>";
                echo '</tr>';

            }
        echo '</tbody>
        </table>';
}

function GetTime($Stib, $client, $StopID, $StopName)
{
    echo "<button onclick = 'GetStop(".$StopID.", \"".$StopName."\")'>Reload</button>";
    echo '<table data-role="table" data-mode="columntoggle" class="ui-responsive" id="myTable">
  <thead>
    <tr>';
        echo '<th>Destination</th>';
        echo '<th data-priority="1">Minutes</th>';
        echo '<th data-priority="2">Line</th>';
        echo '<th>Schedule</th>';
        echo '<th>Stop</th>';
        echo '</tr>
    </thead>
    <tbody>';
        $time = $Stib->GetStop ($client, $StopID, $StopName);
        echo '<pre>';print_r($time);print '</pre>';
        foreach ($time->results as $stop)
            {
                echo '<tr>';
                echo "<td>".$stop['destination']."</td>";
                echo "<td>".$stop['minutes']."</td>";
                echo "<td>".$stop['line']."</td>";
                $schedule = date('G:i', strtotime($stop['when']));
                echo "<td>".$schedule."</td>";
                echo "<td>".$time->name."</td>";
                echo '</tr>';

            }
        echo '</tbody>
        </table>';
}
?>
<body>
<script language = "javascript" type = "text/javascript">
    function GetLine(line){

        $.ajax({url: "index.php?GetLine=1&LineNumber="+line, success: function(result){
                $("#presentation").html(result);
            }});

    }
function GetStops(line, direction){

    $.ajax({url: "index.php?GetStops=1&LineNumber="+line+"&Direction="+direction, success: function(result){
            $("#presentation").html(result);
        }});
}
function GetStop(stopid, stopname){

    $.ajax({url: "index.php?GetStop=1&StopID="+stopid+"&StopName="+stopname, success: function(result){
            $("#presentation").html(result);
        }});
}

</script>
<div id='presentation' name="presentation">
<?php
    if (isset($_GET['GetLine']) && isset($_GET['LineNumber']))
        {
            GetLine($Stib, $client, $_GET['LineNumber']);
        }
elseif (isset($_GET['GetStops']) && isset($_GET['LineNumber']) && isset($_GET['Direction']))
    {
        GetStops($Stib, $client, $_GET['LineNumber'], $_GET['Direction']);
    }
elseif (isset($_GET['GetStop']) && isset($_GET['StopID']) && isset($_GET['StopName']))
    {
        GetTime($Stib, $client, $_GET['StopID'], $_GET['StopName']);
    }
else
    {
        PresentLines($Stib, $client);
    }
?>
</div>
</body>
