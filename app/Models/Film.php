<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "episode_id",
        "opening_crawl",
        "director",
        "producer",
        "release_date",
        "url"
    ];

    public static function createFilm($title, $episode_id, $opening_crawl, $director, $producer, $release_date, $url)
    {
        try {
            $film                = new Film();
            $film->title         = $title;
            $film->episode_id    = $episode_id;
            $film->opening_crawl = $opening_crawl;
            $film->director      = $director;
            $film->producer      = $producer;
            $film->release_date  = $release_date;
            $film->url           = $url;
            $film->save(); 

            return $film->id;
        } catch (Exception $e) {
            // Handle any exceptions that occur during the data storage process
            return false;
        }
    }
    
    public function characters() {
        return $this->hasMany(Character::class,'film_id');
    }
    public function planets() {
        return $this->hasMany(Planet::class,'film_id');
    }
    public function starships() {
        return $this->hasMany(Starship::class,'film_id');
    }
    public function vehicles() {
        return $this->hasMany(Vehicle::class,'film_id');
    }
    public function species() {
        return $this->hasMany(Specie::class,'film_id');
    }
}
