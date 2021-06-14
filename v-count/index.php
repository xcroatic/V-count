<?php

$startDate = date("Y-m-d", strtotime("-7 days"));
$endDate = date("Y-m-d");

$post_array  =  array(
    'username' => "tebcss",
    'password' => "TEB2003css",
    'format' => "json",
    'start_date' => $startDate,
    'finish_date' => $endDate,
    'store' => "TEB Office",
);

//Cloud Url
$url = "https://cloud.v-count.com/api/v4/vcountapi_daily";
//cURL stuff
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);

//Opening file to write to
$file_json = fopen('data.json', 'w');

//Writing to file
$file_text =  curl_setopt($ch, CURLOPT_FILE, $file_json);

$output = curl_exec($ch);

//Catching cURL errors
$curlerrcode = curl_errno($ch);
$curlerr = curl_error($ch);

//Closing cURL

//Getting json file contents
$file_json_contents = file_get_contents('data.json');

//Making json file more readable
$contents_encode = json_encode(json_decode($file_json_contents), JSON_PRETTY_PRINT);
$pretty_json = json_encode(json_decode($contents_encode), JSON_PRETTY_PRINT);
replace_json_pretty('data.json', $file_json_contents, $pretty_json);


//Printing the data for the user
$json = json_decode($file_json_contents, true);
$days = [];
$trafficin = [];
$trafficout = [];
foreach ($json as $key => $value) {
    foreach ($value as $key => $val) {
        if ($key == "store") {
            $key = "Location";
        } else if ($key == "timeformatted") {
            $key = "Time";
            array_push($days, current(explode(" ", $val)));
        } else if ($key == "in") {
            $key = "People Entered";
            array_push($trafficin, $val);
        } else if ($key == "out") {
            $key = "People Exited";
            array_push($trafficout, $val);
        }
    }
}

//Funtction that replaces the old with the new json string
function replace_json_pretty($filename, $to_replace, $replace_with)
{
    $file_content = file_get_contents($filename);
    $file_concent_chunks = explode($to_replace, $file_content);
    $file_content = implode($replace_with, $file_concent_chunks);
    $file_content = '{  "traffic":'. $file_content . "}";
    file_put_contents($filename, $file_content);
}

//Closing items
curl_close($ch);
fclose($file_json);
?>

<html>

<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<link rel="stylesheet" href="css/main.css">
</head>

<body>
<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 fs-2">Dashboard</span>
    <span class="fs-2 fw-light datepicker">Current Date</span>
  </div>
</nav>
    <div style="width: 500px;">
        <canvas id="myChart"></canvas>
    </div>
    <form action="index.php" method="POST">
        <input type="date" name="start">
        <input type="date" name="end">
        <input type="submit">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        <?php
        echo "var days = " . json_encode($days) . ";\n";
        echo "var trafficin = " . json_encode($trafficin) . ";\n";
        echo "var trafficout = " . json_encode($trafficout) . ";\n";
        ?>
        var myChart = new Chart(ctx, {
            count: 15,
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                        label: 'In',
                        data: trafficin,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    },
                    {
                        label: 'Out',
                        data: trafficout,
                        backgroundColor: [
                            'rgba(245, 67, 32, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 0, 0, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                }
            }
        });
    </script>
</body>

</html>