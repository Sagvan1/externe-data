<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * API service
 */
class ApiService
{
    private $ApiUrl = "https://api.football-data.org/v4/";

    public function getDataFromApi($endpoint)
    {
        $url = $this->ApiUrl . $endpoint;

        $options = [
            "http" => [
                "method" => "GET",
                "header" => "X-Auth-Token: 74457f2a0bfe463ebd96fe50cdb6e02a"
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ];

        $context = stream_context_create($options);

        $json = file_get_contents($url, false, $context);

        $result = json_decode($json, true);

        return  $result;
    }


}




