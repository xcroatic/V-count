<?php
echo "
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>";
if (!empty($_POST)) {
    $startDate = $_POST['start'];
    $endDate = $_POST['end'];
} else {
    $startDate = date("Y-m-d", strtotime("-6 days"));
    $endDate = date("Y-m-d");
}
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

$file_text = curl_exec($ch);

//Catching cURL errors
$curlerrcode = curl_errno($ch);
$curlerr = curl_error($ch);


//Getting json file contents
$file_json_contents = file_get_contents('data.json');

//Making json file more readable
$contents_encode = json_encode(json_decode($file_json_contents), JSON_PRETTY_PRINT);
$pretty_json = json_encode(json_decode($contents_encode), JSON_PRETTY_PRINT);
replace_json_pretty('data.json', $file_json_contents, $pretty_json);

//Funtction that replaces the old with the new json string
function replace_json_pretty($filename, $to_replace, $replace_with)
{
    $file_content = file_get_contents($filename);
    $file_concent_chunks = explode($to_replace, $file_content);
    $file_content = implode($replace_with, $file_concent_chunks);
    $file_content = '{  "traffic":' . $file_content . "}";
    file_put_contents($filename, $file_content);
}

//Closing items
curl_close($ch);
fclose($file_json);


?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-all.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-light bg-light border-bottom border-1 border-secondary">
        <span class="navbar-brand mb-0 fs-2 p-3">Dashboard</span>
        <span class="navbar-brand mb-0 fs-2">
            <button class="button fs-2" id="datepicker" data-role="popover" data-popover-text='
            <div class="dateform d-flex justify-content-center flex-column align-items-center text-center p-3">
                <form action="index.php" method="POST" onsubmit="dateChanger()">
                    <label for="start" class="m-2">Choose a starting date:</label>
                    <input data-role="datepicker" data-distance="1" type="date" required name="start" id="start" value="<?php echo $startDate ?>" max="<?php echo date("Y-m-d", strtotime("-1 days")); ?>" >
                    <label for="end" class="m-2">Choose an ending date:</label>
                    <input data-role="datepicker" data-distance="1" type="date" required name="end" id="end" max="<?php echo date("Y-m-d"); ?>" onchange="dateCheck()" value="<?php echo $endDate ?>">
                    <input type="submit" value="Change Date" class="mt-4">
                </form>
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <input type="checkbox" onclick="compareDates()" id="check" data-role="checkbox">
                    <label for="comparisons">Compare dates</label>
                </div>
            </div>
            ' data-popover-position="bottom" data-popover-hide="0">
                Change Date
            </button>
        </span>

        </div>
    </nav>
    <div class="w-100 d-flex justify-content-center flex-column flex-wrap text-center border-bottom">
        <p class="fs-2">See your store data!</p>
        <div class="d-flex flex-wrap justify-content-around">

            <div class="p-3 bg-light text-center">
                <div class="btn-group">
                    <a class="btn btn-primary" id="enter" aria-current="page">In</a>
                    <a class="btn btn-primary" id="exit" >Out</a>
                    <a class="btn btn-primary active" id="enterAndExit" >In & Out</a>
                </div>
                <div class="chart">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="m-3 border-bottom p-3">
                <div>
                    <p class="fs-4">Total traffic for desired date:</p>
                    <p id="peopleIn"></p>
                    <p id="peopleOut"></p>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.metroui.org.ua/v4.3.2/js/metro.min.js"></script>
    <script src="scripts/js/chart.js"></script>

</body>

</html>