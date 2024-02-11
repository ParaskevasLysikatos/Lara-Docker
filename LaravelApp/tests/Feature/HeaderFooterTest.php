<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use GuzzleHttp\Client;
use Tests\TestCase;

class HeaderFooterTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_the_header_contents_exist(): void // the contents are  a part of welcome page
    {
        $response = $this->get('/');

        $response->assertSee('<!DOCTYPE html>',false);
        $response->assertSee('<html style=" position: relative;min-height: 100%;"class="h-100"',false);
        $response->assertSee('<head>',false);
        $response->assertSee('<meta charset="utf-8">',false);
        $response->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1">',false);

        $response->assertSee('<title>Laravel-Aylo</title>',false);
        $response->assertSee('<body class="d-flex flex-column h-100">',false);
        $response->assertSee('<main class="flex-shrink-0">',false);
    }


    public function test_the_header_contents_for_welcome_page(): void // the contents are  a part of welcome page
    {
        $response = $this->get('/');

        $response->assertSee('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"',false);
        $response->assertSee('<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"',false);
        $response->assertSee('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"',false);
        $response->assertSee('<link href="http://localhost/css/visitCard.css" rel="stylesheet">',false);
        $response->assertSee('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />',false);
    }

    public function test_the_header_contents_for_internet_connection(): void // the contents are  a part of welcome page
    {
        $client = new Client();

        $response = $client->get('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
        $statusCode = $response->getStatusCode();

        $this->assertEquals(200,$statusCode);
    }

    public function test_the_header_contents_for_profile_page(): void // the contents are  a part of welcome page
    {
        $response = $this->get('/profile/2');

        $response->assertSee('<link href="http://localhost/css/profileCard.css" rel="stylesheet">',false);
        $response->assertSee('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">',false);
        $response->assertSee('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>',false);
        $response->assertSee('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>',false);
    }

    public function test_the_footer_contents_exist(): void // the contents are  a part of welcome page
    {
        $response = $this->get('/');

        $response->assertSee('</main>',false);
        $response->assertSee('<footer style="bottom:0;position: absolute;width: 100%;height: 50px;',false);
        $response->assertSee('<div class="container-xl d-flex justify-content-center">',false);
        $response->assertSee('© 2024 Copyright:',false);
        $response->assertSee('<a class="text-reset fw-bold" href="https://www.linkedin.com/in/paraskevas-lysikatos/">Lysikatos Paraskevas</a>',false);

        $response->assertSee('</body>',false);
        $response->assertSee('</html>',false);
    }
 }
