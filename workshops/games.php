<?php
/*
 * Author: Sagvan Alfatah
 * Date: 22-4-2026
 * Games class
 */

class Game {

    public $game_id;
    public $titel;
    public $descriptoin;
    public $releasd_at;
    public $personal_rating;
    public $genre_id;
    public $platform_id;
    public $rawg_id;
    public $rawg_ratind;
    public $created_at;
    public $updated_at;

    //construct
    public function __construct($game_id, $titel,
        $descriptoin, $releasd_at, $personal_rating, $genre_id, $platform_id,
                                $rawg_id, $rawg_ratind,  $created_at,$updated_at )
    {

        $this->id = $game_id;
        $this->titel = $titel;
        $this->descriptoin = $descriptoin;
        $this->releasd_at = $releasd_at;
        $this->personal_rating = $personal_rating;
        $this->genre_id = $genre_id;
        $this-> platform_id =  $platform_id;
        $this->rawg_id = $rawg_id;
        $this->rawg_ratind = $rawg_ratind;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function toonInfo()
    {
        return $this->id . "," . $this->titel . "," . $this->descriptoin . "," .$this->releasd_at;
    }
}