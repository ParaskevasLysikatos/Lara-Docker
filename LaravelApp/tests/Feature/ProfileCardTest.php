<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCardTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_the_profile_card_contents_exist(): void
    {
        $response = $this->get('/profile/2');

        $response->assertSee(@include('header'));
        $response->assertSee('<div class="container emp-profile">',false);
        $response->assertSee('<div class="profile-img">',false);
        $response->assertSee('<img width="250" height="350"' ,false);
        $response->assertSee('<div class="profile-head">',false);

        $response->assertSee('<p class="proile-rating">World Status :',false);
        $response->assertSee('<ul class="nav nav-tabs">',false);

        $response->assertSee('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home">Attributes</a></li>',false);

        $response->assertSee('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">Stats</a></li>',false);
        $response->assertSee('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu2">Thumbnails</a></li>',false);

        $response->assertSee('<a href="/?page="> <input type="button" class="profile-edit-btn" name="btnAddMore" value="Go Back" /> </a>',false);
        $response->assertSee('<div class="tab-content profile-tab">',false);
        $response->assertSee('<div id="home" class="tab-pane fade in active">',false);
        $response->assertSee('<p class="proile-rating">Hair Color :',false);

        $response->assertSee('<div id="menu1" class="tab-pane fade">',false);
        $response->assertSee('<div id="menu2" class="tab-pane fade">',false);
        $response->assertSee('<div class="profile-work"> <p>PORNHUB LINK</p>',false);
        $response->assertSee('</main>',false);
        $response->assertSee('<footer>',false);

        $response->assertSee('2024 Copyright: <a class="text-reset fw-bold" href="https://www.linkedin.com/in/paraskevas-lysikatos/"> LysikatosParaskevas',false);
        $response->assertSee('</body>',false);
        $response->assertSee('</html>',false);

    }

    public function test_the_profile_card_page_not_exist(): void //
    {
        $response = $this->get('/profile/1?page=1');

        // Assert redirect back to homepage
        $response->assertRedirect('/');

        // Assert error message set in session
        $response->assertSessionHas('error', 'Actor not found');
    }
}
