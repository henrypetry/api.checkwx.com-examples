<?php
$ch = curl_init();
// KMIA is the airport code for Miami International
curl_setopt($ch, CURLOPT_URL, 'https://api.checkwx.com/metar/KMIA/decoded');
// You must supply your own X-API-Key from api.checkwx.com
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-Key: your-own-api-key']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (isset($response['results'])) {
    if ($response['results'] == 0) {
        $raw = "No results returned from API";
    } else {
        $metar = $response['data'][0];
        $icao = $metar['icao'];
        $name = $metar['station']['name'];
        $raw = $metar['raw_text'];
    }
} else {
    $icao = $response['statusCode'];
    $name = $response['error'];
    $raw = $response['message'];
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Simple example of using PHP to display METAR weather from api.checkwx.com">

    <title>METAR Weather Example</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    crossorigin="anonymous">
</head>


<body>

    <div class="container mt-5">
        <h2>METAR</h2>
        <h4><span id="icao"><?php echo $icao; ?></span> <span id="name"><?php echo $name; ?></span></h4>
        <hr>
        <pre id="raw"><?php echo $raw; ?></pre>
        <hr>

        <?php if(isset($metar['wind'])) { ?>
            <div id="wind_block">
                <h5>Wind</h5>
                <ul>
                    <li>
                        <span id="wind_direction"><?php echo $metar['wind']['degrees']; ?></span> @ <span id="wind_speed"><?php echo $metar['wind']['speed_kts']; ?></span><small> knots</small>
                    </li>
                </ul>
            </div>
        <?php } ?>

        <?php if(isset($metar['visibility'])) { ?>
            <div id="visibility_block">
                <h5>Visibility</h5>
                <ul>
                    <li>
                        <span id="visibility_miles"><?php echo $metar['visibility']['miles']; ?></span><small> miles</small>
                    </li>
                </ul>
            </div>
        <?php } ?>

        <?php if(isset($metar['clouds']) && count($metar['clouds']) > 0) { ?>
            <div id="cloud_block">
                <h5>Clouds</h5>
                <ul id="cloud_list">
                    <?php foreach($metar['clouds'] as $cloud) { ?>
                        <li><?php echo $cloud['text']; ?> at  <?php echo $cloud['base_feet_agl']; ?><small> feet AGL</small></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if(isset($metar['conditions']) && count($metar['conditions']) > 0) { ?>
            <div id="cond_block">
                <h5>Conditions</h5>
                <ul id="cond_list">
                <?php foreach($metar['conditions'] as $cond) { ?>
                    <li><?php echo $cond['text']; ?></li>
                <?php } ?>
                </ul>
            </div>
        <?php } ?>

    </div>

</body>

</html>