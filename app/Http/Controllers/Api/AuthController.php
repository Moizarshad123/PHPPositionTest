<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Film;


class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'     => 'required', //|regex:/(0)[0-9]{10}/
            'password'  => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
            } else {
                return $this->error("Invalid Credentials");
            }
        } else {
            return $this->error("Invalid Credentials");
        }

        $user->api_token =  auth()->user()->createToken('API Token')->plainTextToken;
        $user->save();
        return $this->success($user);
    }

    public function logout(Request $request) {
        $user_id                  = Auth::user()->id;
        $update_status            = User::find($user_id);
        $update_status->is_active = 0;
        $update_status->save();

        Auth::user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function unauthenticatedUser() {
        return $this->error('Unauthorized', 401);
    }

    public function fetch_films() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://swapi.dev/api/films/",// your preferred link
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
        $err = curl_error($curl);
        curl_close($curl);
        $res = json_decode($response);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            
            foreach ($res->results as $item) {
                $check = Film::where('title', $item->title)->first();
                if($check == null) {
                    $film = Film::createFilm($item->title, $item->episode_id, $item->opening_crawl, $item->director, $item->producer, $item->release_date, $item->url);
                }
            }

            return $this->success([], "Films added successfully...!!");
        }

        
    }


}
