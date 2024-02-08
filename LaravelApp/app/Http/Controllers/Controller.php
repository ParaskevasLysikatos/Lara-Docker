<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

// Import the GuzzleHttp\Client class
use GuzzleHttp\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function welcome(){ // changes will be needed for json data

       // $users= User::paginate(5);

        $users=Cache::remember('users-page-'.request('page',1), 60 * 60, function(){
            return User::paginate(5);
        });

        $this->getJsonData();

        return view('welcome', [
            'users' => $users
        ]);

    }


    public function getJsonData(){ // small changes
        // Create a new Guzzle client instance
        $client = new Client();

        // Specify the URL you want to make a GET request to
        $url = 'https://api.sampleapis.com/beers/ale';

        // Make a GET request
        $response = $client->get($url);

        // Get the response body as a string
        $body = $response->getBody()->getContents();

        // You can then parse the JSON response if needed
        $data = json_decode($body, false);

        // Do something with the response data
        // dd($data[0]->price);
        return $data;
    }

    public function cachingImages(){ // not ready
        $imageData = $this->getJsonData();

        // Cache the images
        foreach ($imageData['images'] as $imageUrl) {
            // Generate a cache key based on the image URL
            $cacheKey = 'image_' . md5($imageUrl);

            // Check if the image is already in the cache
            if (!cache()->has($cacheKey)) {
                // If not, fetch the image and store it in the cache for, let's say, 24 hours
                $imageContent = file_get_contents($imageUrl);
                cache()->put($cacheKey, $imageContent, now()->addDay());
            }
        }
    }

}
