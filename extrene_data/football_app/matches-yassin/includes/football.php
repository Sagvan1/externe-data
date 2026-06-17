<?php
/*
 * Author: R. Evers
 * Date: 20-5-26
 * Class: football class
 */

class Matchfootball
{

    private PDO $conn;
    private string $apiUrl = "https://pokeapi.co/api/v2/pokemon?limit=20";

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function create(
        int $id,
        string $utcDate,
        string $status,
        int $matchday,
        int $home_team_id,
        int $away_team_id,
        int $competition_id
    )
    {
        $sql = "INSERT INTO matches 
            (id, utcDate, status, matchday, home_team_id, away_team_id, competition_id)
            VALUES 
            (:id, :utcDate, :status, :matchday, :home_team_id, :away_team_id, :competition_id)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":utcDate", $utcDate);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":matchday", $matchday);
        $stmt->bindParam(":home_team_id", $home_team_id);
        $stmt->bindParam(":away_team_id", $away_team_id);
        $stmt->bindParam(":competition_id", $competition_id);

        return $stmt->execute();
    }

    public function readAll()
    {
        $sql= " SELECT matches.id, matches.utcDate, matches.status, matches.matchday, home.name AS homeTeam, away.name AS awayTeam, competitions.name AS competition
FROM matches
JOIN teams home on home.id = matches.home_team_id
JOIN teams away on away.id = matches.away_team_id
JOIN competitions on matches.competition_id = competitions.id;";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePokemon(int $id, string $name, string  $image)
    {
        $sql = "DELETE FROM pokemon SET name = :name, image = :image WHERE id = :id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

    }

    public function update(
        int $id,
        string $utcDate,
        string $status,
        int $matchday,
        int $home_team_id,
        int $away_team_id,
        int $competition_id
    )
    {
        $sql = "UPDATE matches SET 
                utcDate = :utcDate,
                status = :status,
                matchday = :matchday,
                home_team_id = :home_team_id,
                away_team_id = :away_team_id,
                competition_id = :competition_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":utcDate", $utcDate);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":matchday", $matchday);
        $stmt->bindParam(":home_team_id", $home_team_id);
        $stmt->bindParam(":away_team_id", $away_team_id);
        $stmt->bindParam(":competition_id", $competition_id);

        return $stmt->execute();
    }
    private function getPokemonListFromApi(): array
    {
        $json = file_get_contents($this->apiUrl);

        if ($json === false) {
            return [];
        }

        $data = json_decode($json, true);

        return $data["results"] ?? [];
    }
    public function getPokemonByApi(): array
    {
        $pokemonList = $this->getPokemonListFromApi();
        $pokemonDetails = $this->getPokemonDetailsFast($pokemonList);

        $pokemons = [];

        foreach ($pokemonList as $key => $pokemon) {
            $detail = $pokemonDetails[$key] ?? [];

            $pokemons[] = [
                "name" => $pokemon["name"],
                "image" => $detail["sprites"]["back_default"] ?? "",
            ];
        }

        return $pokemons;
    }

    private function getPokemonDetailsFast(array $pokemonList): array
    {
        $multiHandle = curl_multi_init();
        $curlHandles = [];

        foreach ($pokemonList as $key => $pokemon) {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $pokemon["url"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
            ]);

            curl_multi_add_handle($multiHandle, $curl);
            $curlHandles[$key] = $curl;
        }

        $running = null;
        do {
            curl_multi_exec($multiHandle, $running);
            curl_multi_select($multiHandle);
        } while ($running > 0);

        $details = [];

        foreach ($curlHandles as $key => $curl) {
            $response = curl_multi_getcontent($curl);
            $details[$key] = json_decode($response, true);

            curl_multi_remove_handle($multiHandle, $curl);
            curl_close($curl);
        }

        curl_multi_close($multiHandle);

        return $details;
    }
    public function delete(int $id): void
    {
        // Eerst de wedstrijd ophalen met team namen via JOIN
        $stmt = $this->conn->prepare(
            "SELECT home.name AS homeTeam, away.name AS awayTeam 
         FROM matches 
         JOIN teams home ON home.id = matches.home_team_id
         JOIN teams away ON away.id = matches.away_team_id
         WHERE matches.id = ?"
        );
        $stmt->execute([$id]);

        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        // Loggen (controleer eerst of $match niet leeg is)
        if ($match) {
            $log = date('Y-m-d H:i:s') .
                " - {$match['homeTeam']} vs {$match['awayTeam']} verwijderd" .
                PHP_EOL;

            file_put_contents(
                'deleted_matches.log',
                $log,
                FILE_APPEND
            );
        }

        // Daarna verwijderen - fix: gebruik $this->conn in plaats van $this->db
        $stmt = $this->conn->prepare("DELETE FROM matches WHERE id = ?");
        $stmt->execute([$id]);
    }
    public function searchFootball(string $search)
    {
        // quiry die een pokemon zoekt met deze naam
        $sql = "SELECT matches.*,
               home.name AS homeTeam,
               away.name AS awayTeam,
               competitions.name AS competition
        FROM matches
         JOIN teams home
            ON home.id = matches.home_team_id
         JOIN teams away
            ON away.id = matches.away_team_id
         JOIN competitions
            ON competitions.id = matches.competition_id
        WHERE home.name LIKE :search
           OR away.name LIKE :search";
        $stmt = $this->conn->prepare($sql);

        $search= "%". $search . "%";
        $stmt->bindParam(":search", $search);

        $stmt->execute();

        return $stmt->fetchAll();
    }

}
