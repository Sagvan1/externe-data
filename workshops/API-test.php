<?php

$apiKey = "74457f2a0bfe463ebd96fe50cdb6e02a";

// API URL
$base_url = "http://api.football-data.org";

$endpoint = "/v4/persons/265343";


//$endpoint = "/v4/teams/678";
//$endpoint = "/v4/competitions/DED/standings";
//$endpoint = "/v4/competitions/DED";
//$endpoint = "/v4/competitions/DED/matches";


$url = $base_url . $endpoint;

// Headers toevoegen
$options = [
    "http" => [
        "method" => "GET",
        "header" => "X-Auth-Token: $apiKey"
    ]
];

// Context maken
$context = stream_context_create($options);

// API call uitvoeren
$response = file_get_contents($url, false, $context);

// JSON omzetten naar array
$data = json_decode($response, true);

echo "<pre>";
print_r($data);

