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

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function profile(Request $request){
        return view('profileCard', [
          //  'id' => $request->id
        ]);
    }

    public function welcome()
    {
        // cache the whole collection for an 30 minutes
        $collection = Cache::remember('actors', now()->addMinutes(30), function () {

        $alldata = $this->getJsonData();
        // dd($alldata->items[0]->attributes->age);
        Cache::put('currentDatetime', $alldata->generationDate, now()->addMinutes(30));

        $actors=[];


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




        return view('welcome', [
            'currentDate' => \Carbon\Carbon::parse(Cache::get('currentDatetime'))->format('d/m/Y H:s:i'),
            'actors' => $collection->paginate(10)
        ]);
    }


    public function getJsonData()
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


    public function cachingImages()
    { // not ready
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
