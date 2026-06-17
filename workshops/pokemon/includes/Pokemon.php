<?php
/*
 * Author: R. Evers
 * Date: 20-5-26
 * Class: Pokemon class
 */

class Pokemon
{

    private PDO $conn;
    private string $apiUrl = "https://pokeapi.co/api/v2/pokemon?limit=20";

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function create(string $name, string $image, string $weight)
    {
        $sql = "INSERT INTO pokemon (name, image, weight) VALUES (:name, :image, :weight)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":weight", $weight);
        $stmt->bindParam(":image", $image);

        $stmt->execute();

    }


    public function delete($id)
    {
        $sql = "DELETE FROM pokemon WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);

        $stmt->execute();
    }


    public function update( int $id, string $name, string $image, string $weight)
    {
      $sql =  "UPDATE pokemon SET name = :name, image = :image, :weight $weight WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":weight", $weight);

        $stmt->execute();

    }

    public function readAll()
    {
        $sql = "SELECT pokemon.id, pokemon.name, pokemon.image, pokemon_type.name, pokemon_type.description
                FROM pokemon
                LEFT JOIN pokemon_type ON pokemon.type_id = pokemon_type.id;";

        $stmt = $this->conn->query($sql);


        $stmt->execute();

        return $stmt->fetchAll();

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
                "weight" => $detail["weight"] ?? "",
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


    public function searchPokemon(string $search)
    {
        //query die pokemon zoekt met $search
        $sql = "SELECT * FROM pokemon WHERE name LIKE :search";
        $stmt = $this->conn->prepare($sql);

        $search = "%". $search."%";

        $stmt->bindParam(":search", $search);

        //Uitvoeren query
        $stmt->execute();

        //Resulaten ophalen en terugsturen
        return $stmt->fetchAll();
    }
}