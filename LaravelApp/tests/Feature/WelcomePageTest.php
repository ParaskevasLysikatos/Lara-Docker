<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_welcome_page_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_welcome_page_contents_exist(): void
    {
        $response = $this->get('/');

        $response->assertSee(@include('header'));
        $response->assertSee('Welcome to List of profiles, last updated:');
        $response->assertSee('Visit every profile for more info by clicking on the corresponding card.');
        $response->assertSee(@include('visitCard'));
        $response->assertSee(@include('footer'));
    }

    public function test_the_welcome_page_exceed_page_limit_redirects_back(): void
    {
        $response = $this->get('/?page=3000');

        // Assert redirect back to homepage
        $response->assertRedirect('/');

        // Assert error message set in session
        $response->assertSessionHas('error', 'Page does not exist');
    }
}
