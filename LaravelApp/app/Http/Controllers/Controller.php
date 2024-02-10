<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use App\Classes\AdultActorClass;
use App\Classes\AttributeClass;
use App\Classes\StatClass;
use App\Classes\ThumbnailClass;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    private function serveImage($key, $savedName) // saves the image to storage from redis cache
    {
        // Your URL encoded image data
        $imageString = Cache::get($key);
        // dd($imageData);

        $tempImagePath = public_path('servedImages/' . $savedName . '.jpg');
        File::put($tempImagePath, $imageString);

        return $tempImagePath;
    }


    public function profile() // displays the profile of one actor
    {

        if (Cache::has('currentDatetime') && Cache::has('actorsCollection')) {
            $collection = Cache::get('actorsCollection');
        } else {
            $collection = $this->cacheJsonData();
        }

        //dd(request()->segment(2));
        $currentActor = $collection->firstWhere('id', '=', request()->segment(2));

        //dd($currentActor);

        return view('profileCard', [
            'actor' => $currentActor,
            'page' => request('page')
        ]);
    }

    public function welcome() // first page of the app
    {
        // check if we have the json data cache
        if (Cache::has('currentDatetime') && Cache::has('actorsCollection')) {
            $collection = Cache::get('actorsCollection');
        } else {
            // clean both because cache last a day, but data 30 min, after wie will have inconsistency
            $this->cleanLocalFolder();
            Cache::flush();

            $collection = $this->cacheJsonData();
        }

        // get all the image paginated cached keys
        $paginatedImageKeys = [];
        foreach ($collection->paginate(10) as $col) {
            foreach ($col->thumbnail as $th) {
                $key = 'cachedImage_' . $th->urls;
                array_push($paginatedImageKeys, $key);
            }
        }

        // check and cache the images (for 10 actors) for day of the current page
       // dd($paginatedImageKeys);
       // dd($this->checkCacheKeys($paginatedImageKeys));
        if (!$this->checkCacheKeys($paginatedImageKeys)) {
            $this->cachingImagesRedis($collection->paginate(10));
            // dd(urlencode(Cache::get('cachedImage_'.$collection[0]->thumbnail[0]->urls)));
            // bring all paginated images to local storage
            $servedImagespaths = [];
            $counter = 0;
            foreach ($collection->paginate(10) as $col) {
                if (count($col->thumbnail) > 0) {
                    $path = $this->serveImage('cachedImage_' . $col->thumbnail[0]->urls, $col->id . '_' . $col->thumbnail[0]->type);
                    $servedImagespaths[$counter] = explode('/', $path)[6];  // "/var/www/html/public/servedImages/17_pc.jpg"
                    foreach ($col->thumbnail as $th) { // needed for profile
                        $path2 = $this->serveImage('cachedImage_' . $th->urls, $col->id . '_' . $th->type);
                    }
                }
                $counter++;
            }
        }
        else{
            $servedImagespaths = [];
            $counter = 0;
            foreach ($collection->paginate(10) as $col) {
                if (count($col->thumbnail) > 0) {
                    $path = $col->id . '_' . $col->thumbnail[0]->type.'.jpg';
                    $servedImagespaths[$counter] = $path;  // 17_pc.jpg"
                }
                $counter++;
            }

        }

        // dd($collection->paginate(10)->merge($servedImagespaths));

        return view('welcome', [
            'currentDate' => \Carbon\Carbon::parse(Cache::get('currentDatetime'))->format('d/m/Y H:s:i'),
            'actors' => $collection->paginate(10),
            'servedImages' => $servedImagespaths
        ]);
    }


    private function getJsonData()
    { // small changes

        // Create a new Guzzle client instance
        $client = new Client();

        // Specify the URL you want to make a GET request to
        $url = 'https://www.pornhub.com/files/json_feed_pornstars.json';

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

    private function cacheJsonData()
    {
        // clean the two caches so to expire together
        Cache::forget('actorsCollection');
        Cache::forget('currentDatetime');
        // cache the whole collection for an 30 minutes
        $resultCollection = Cache::remember('actorsCollection', now()->addMinutes(30), function () {

            $alldata = $this->getJsonData();
            // dd($alldata->items[0]->attributes->age);
            Cache::put('currentDatetime', $alldata->generationDate, now()->addMinutes(30));

            $actors = [];


            foreach ($alldata->items as $item) {

                $actorAttrStats = new StatClass(
                    $item->attributes->stats->subscriptions,
                    $item->attributes->stats->monthlySearches,
                    $item->attributes->stats->views,
                    $item->attributes->stats->videosCount,
                    $item->attributes->stats->premiumVideosCount,
                    $item->attributes->stats->whiteLabelVideoCount,
                    $item->attributes->stats->rank,
                    $item->attributes->stats->rankPremium,
                    $item->attributes->stats->rankWl
                );

                $actorAttr = new AttributeClass(
                    $item->attributes->hairColor ?? null,
                    $item->attributes->ethnicity ?? null,
                    $item->attributes->tattoos,
                    $item->attributes->piercings,
                    $item->attributes->breastSize ?? null,
                    $item->attributes->breastType ?? null,
                    $item->attributes->gender,
                    $item->attributes->orientation,
                    $item->attributes->age ?? null,
                    $actorAttrStats
                );

                // loop to get the thumbnails
                $arrayOfThumbs = [];
                foreach ($item->thumbnails as $thumb) {
                    $thumbnail = new ThumbnailClass(
                        $thumb->height,
                        $thumb->width,
                        $thumb->type,
                        $thumb->urls[0]
                    );
                    array_push($arrayOfThumbs, $thumbnail);
                }

                // loop to get aliases
                $arrayOfAliases = [];
                foreach ($item->aliases as $alias) {
                    array_push($arrayOfAliases, $alias);
                }


                $actor = new AdultActorClass(
                    $actorAttr,
                    $item->id,
                    $item->name,
                    $item->license,
                    $item->wlStatus,
                    $arrayOfAliases,
                    $item->link,
                    $arrayOfThumbs
                );

                // finally push the actor to actors array
                array_push($actors, $actor);
            }

            $collection = collect($actors);
            //dd($collection->paginate(10));
            return  $collection;
        });
        return  $resultCollection;
    }


    private function cachingImagesRedis($paginatedData) // 10 by 10 actors otherwise will timeout
    {

        // collect all the images urls
        $imageUrls = [];
        // dd($paginatedData);

        foreach ($paginatedData as $c) {
            foreach ($c->thumbnail as $thumb) {
                array_push($imageUrls, $thumb->urls);
            }
        }

        //dd($imageUrls);
        // $countCachedImages=0;
        // Cache the images
        foreach ($imageUrls as $url) {
            // Generate a cache key based on the image URL
            $cacheKey = 'cachedImage_' . $url;
            // Check if the image is already in the cache
            if (!Cache::has($cacheKey)) {
                // If not, fetch the image content using cURL
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $imageContent = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    // Handle the error (e.g., log it, display a default image, etc.)
                    error_log('cURL error: ' . curl_error($ch));
                }

                curl_close($ch);

                // Store the image content in the cache for, let's say, 24 hours
                Cache::put($cacheKey, $imageContent, now()->addDay());
                // $countCachedImages++;
            }
        }
    }

    private function cleanLocalFolder()
    {
        $folderPath = public_path('servedImages');

        // Check if the folder exists before proceeding
        if (File::exists($folderPath)) {
            // Delete all files within the folder
            File::cleanDirectory($folderPath);
        }
    }

    private function checkCacheKeys($arrayKeys)
    {
        foreach ($arrayKeys as $key) {
            if (!Cache::has($key)) {
                return false;
            }
        }
        return true;
    }
}
