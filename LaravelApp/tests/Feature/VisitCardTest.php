<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitCardTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_the_visit_card_contents_exist(): void // the contents are  a part of welcome page
    {
        $response = $this->get('/');

        $response->assertSee('<div class="row row-cols-1 row-cols-md-5">',false);
       $response->assertSee('div class="col mb-2">',false);
        $response->assertSee('<form action="profile/',false);
        $response->assertSee('<div class="card h-100" id="hoverCard">' ,false);
        $response->assertSee('<img class="card-img-top" width="250" height="180"',false);
        $response->assertSee('<div class="card-body">',false);
        $response->assertSee('<h5 class="card-title">',false);
        $response->assertSee('<p class="card-text">Aliases:',false);
        $response->assertSee('<input type="hidden" name="page',false);
        $response->assertSee('<nav>',false);
        $response->assertSee('<ul class="pagination">',false);
    }

}
