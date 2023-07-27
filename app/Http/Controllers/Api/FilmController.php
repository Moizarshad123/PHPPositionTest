<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Film;
use App\Models\Character;
use App\Models\Planet;
use App\Models\Starship;
use App\Models\Vehicle;
use App\Models\Specie;

class FilmController extends Controller
{
    public function fetch_movies() {

        try {
            //code...
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://swapi.dev/api/films/",//  preferred link
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);
            $res      = json_decode($response);
    
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                
                foreach ($res->results as $item) {
                    $check = Film::where('title','LIKE','%'.$item->title.'%')->first();
                    if($check == null) {
                        $film = Film::createFilm($item->title, $item->episode_id, $item->opening_crawl, $item->director, $item->producer, $item->release_date, $item->url);
                      
                        if(count($item->characters) > 0) {
                            foreach ($item->characters as $charc) {
                                # code...
                                $character            = new Character();
                                $character->film_id   = $film;
                                $character->character = $charc;
                                $character->save();
                            }
                        }
                        if(count($item->planets) > 0) {
                            foreach ($item->planets as $planet) {
                                # code...
                                $planets          = new Planet();
                                $planets->film_id = $film;
                                $planets->planet  = $planet;
                                $planets->save();
                            }
                        }
                        if(count($item->starships) > 0) {
                            foreach ($item->starships as $starship) {
                                # code...
                                $starships           = new Starship();
                                $starships->film_id  = $film;
                                $starships->starship = $starship;
                                $starships->save();
                            }
                        }
                        if(count($item->vehicles) > 0) {
                            foreach ($item->vehicles as $vehicle) {
                                # code...
                                $vehicles          = new Vehicle();
                                $vehicles->film_id = $film;
                                $vehicles->vehicle  = $vehicle;
                                $vehicles->save();
                            }
                        }
                        if(count($item->species) > 0) {
                            foreach ($item->species as $specie) {
                                # code...
                                $species          = new Specie();
                                $species->film_id = $film;
                                $species->specie  = $specie;
                                $species->save();
                            }
                        }
    
                    }
                }
    
                return $this->success([], "Films added successfully...!!");
            }
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function movies() {

        $movies = Film::with('characters', 'planets', 'starships', 'vehicles', 'species')->get();
        return $this->success($movies);
    }

    public function modify_movie(Request $request) {
    
        try {
            $validator = Validator::make($request->all(), [
                'id'       => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, $validator->errors());
            }
            //code...
            $modify_movie               = Film::find($request->id);
            $modify_movie->title        = $request->title;
            $modify_movie->episode_id   = $request->episode_id;
            $modify_movie->director     = $request->director;
            $modify_movie->producer     = $request->producer;
            $modify_movie->release_date = $request->release_date;
            $modify_movie->url          = $request->url;
            $modify_movie->save();

            return $this->success($modify_movie, "Movie Updated Successfully");
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function delete_movie(Request $request) {
       try {
            //code...
            $validator = Validator::make($request->all(), [
                'id'       => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, $validator->errors());
            }
            Film::where('id', $request->id)->delete();
            return $this->success([], "Movie Delete Successfully...!!");
       } catch (\Exception $e) {
            return $this->error($e->getMessage());
       }

    }

    public function search_movie(Request $request) {
        try {
            //code...
            $search_movie = Film::where('title','LIKE','%'.$request->title.'%')->get();
            return $this->success($search_movie);
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }
}
