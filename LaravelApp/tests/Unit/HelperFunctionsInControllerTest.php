<?php

namespace Tests\Unit;

use App\Classes\AdultActorClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class HelperFunctionsInControllerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_function_getJsonData_returns_json(): void  //  getJsonData function
    {
        $controller = new Controller();
        $response = $controller->getJsonData();

        $this->assertIsObject($response);
        $this->assertJson(json_encode($response));
    }

    public function test_that_function_cleanLocalFolder_deletes_all_items_in_that_folder(): void  // cleanLocalFolder function
    {
        // Create a test folder under public_path()
        $path = 'test_folder';
        $folderPath = public_path('test_folder');

        File::makeDirectory($folderPath);

        // Create sample files in the folder
        File::put($folderPath . '/file1.txt', 'Hello');
        File::put($folderPath . '/file2.txt', 'World');

        // Call the method to clean the test folder
        $controller = new Controller();
        $controller->cleanLocalFolder($path);

        // Assert folder is empty
        $this->assertCount(0, File::allFiles($folderPath));

        // Assert folder still exists
        $this->assertTrue(File::exists($folderPath));

        // Clean up test folder
        File::deleteDirectory($folderPath);
    }

    public function test_that_function_checkCacheKeys_caches_the_given_keys(): void  // checkCacheKeys function
    {
        // Setup - store some keys in cache
        Cache::put('key1', 'value1', 10);
        Cache::put('key2', 'value2', 10);

        // Keys to check
        $keys = ['key1', 'key2'];

        // Run method
        $controller = new Controller();
        $hasKeys = $controller->checkCacheKeys($keys);

        // Assertions
        $this->assertTrue($hasKeys);

        // Check with invalid key
        $keys = ['key1', 'invalid'];
        $hasKeys =  $controller->checkCacheKeys($keys);
        $this->assertFalse($hasKeys);

        // Cleanup cache
        Cache::forget('key1');
        Cache::forget('key2');
    }

    public function test_that_function_getPhysicalImage_returns_an_image(): void  // getPhysicalImage function
    {
        // Test image url
        $url = 'https://cdn2.thecatapi.com/images/0XYvRd7oD.jpg';


        // Call method
        $controller = new Controller();
        $imageContent = $controller->getPhysicalImage($url);

        // Assertions
        $this->assertGreaterThan(5000, strlen($imageContent));

        // Test curl error case
        $url = 'https://cdn2.thecatapi.com/images/0XYvRd7oD.jpg_error';

        $imageContent = $controller->getPhysicalImage($url);

        // Assert null response
        $this->assertLessThan(500, strlen($imageContent));
    }

    public function test_that_function_cachingImagesRedis_caches_the_images_from_links(): void  // cachingImagesRedis function
    {
        // Mock collection of data with image urls
        $paginatedData = [
            (object) ['thumbnail' => [
                (object)  ['urls' => 'https://cdn2.thecatapi.com/images/0XYvRd7oD.jpg']
            ]],
            (object) ['thumbnail' => [
                (object) ['urls' => 'https://cdn2.thecatapi.com/images/ebv.jpg']
            ]]
        ];

        // Call caching method
        $controller = new Controller();
        $controller->cachingImagesRedis($paginatedData);

        // Assert cache has keys
        $this->assertTrue(Cache::has('cachedImage_https://cdn2.thecatapi.com/images/0XYvRd7oD.jpg'));
        $this->assertTrue(Cache::has('cachedImage_https://cdn2.thecatapi.com/images/ebv.jpg'));

        // Cleanup cache
        Cache::forget('cachedImage_https://cdn2.thecatapi.com/images/0XYvRd7oD.jpg');
        Cache::forget('cachedImage_https://cdn2.thecatapi.com/images/ebv.jpg');
    }

    public function test_that_function_cacheJsonData_caches_jsonActors_and_returns_an_actor_collection(): void  // cacheJsonData function
    {
        $controller = new Controller();
        // Call method
        $collection =  $controller->cacheJsonData();

        // Assert object structure
        /** @var \App\ActorClass $actor **/
        foreach ($collection as $actor) {
            $this->assertInstanceOf(AdultActorClass::class, $actor);

            $this->assertIsInt($actor->id);
            $this->assertIsString($actor->name);

            $this->assertObjectHasProperty('attributes', $actor);
            $this->assertObjectHasProperty('thumbnail', $actor); // i forget that is an array
            $this->assertObjectHasProperty('stats', $actor->attributes);
        }

        $this->assertTrue(Cache::has('actorsCollection'));
        $this->assertTrue(Cache::has('currentDatetime'));
    }


    public function test_that_function_serveImage_saves_the_images_in_storage_from_cache(): void  // serveImage function
    {
        $controller = new Controller();
        // Mock data
        $key = 'image_cache_key';
        $savedName = 'test_image';
        $imageContent = 'dummy image content';

        // Mock cache
        Cache::shouldReceive('get')
            ->with($key)
            ->andReturn($imageContent);

        // Call method
        $tempPath = $controller->serveImage($key, $savedName);

        $this->assertEquals(
            public_path("servedImages/{$savedName}.jpg"),
            $tempPath
        );

        $this->assertEquals(
            $imageContent,
            File::get($tempPath)
        );

        // Cleanup
        File::delete($tempPath);
    }
}
