<?php
/*
 * Author: Sagvan
 * Date 22-5-26
 * API service
 */

class ApiService
{
    // Basis-URL van de Football Data API
    private $ApiUrl = "https://api.football-data.org/v4/";

    // Methode om gegevens op te halen uit de API
    public function getDataFromApi($endpoint)
    {
        // Volledige URL samenstellen met het opgegeven endpoint
        $url = $this->ApiUrl . $endpoint;

        // Instellingen voor het HTTP-verzoek
        $options = [
            "http" => [
                // GET-request gebruiken
                "method" => "GET",

                // API-sleutel meesturen voor authenticatie
                "header" => "X-Auth-Token: 74457f2a0bfe463ebd96fe50cdb6e02a"
            ],

            "ssl" => [
                // SSL-controle uitschakelen
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ];

        // Context maken met de bovenstaande instellingen
        $context = stream_context_create($options);

        // JSON-data ophalen van de API
        $json = file_get_contents($url, false, $context);

        // JSON omzetten naar een PHP-array
        $result = json_decode($json, true);

        // Resultaat teruggeven
        return $result;
    }
}