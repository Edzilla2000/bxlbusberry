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
        echo '<th>LineID</th>';
        echo '<th data-priority="1">LineDirection</th>';
        echo '<th data-priority="2">LineName</th>';
        echo '</tr>
    </thead>
    <tbody>';
        $currentline = $Stib->GetLine($client, $line);
        foreach ($currentline->directions as $key => $value)
            {
                echo '<tr>';
                echo "<td><a href='#' onclick = 'ajaxFunction()' id='".$currentline->id."' class='".$currentline->id."'>".$currentline->id."</a></td>";
                echo "<td>".$key."</td>";
                echo "<td>".$currentline->name."</td>";
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

</script>
<div id='presentation' name="presentation">
<?php
    if (isset($_GET['GetLine']) && isset($_GET['LineNumber']))
        {
            GetLine($Stib, $client, $_GET['LineNumber']);
        }
    else
        {
            PresentLines($Stib, $client);
        }
?>
</div>
</body>