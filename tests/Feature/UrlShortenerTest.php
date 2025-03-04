<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ShortUrl;

class UrlShortenerTest extends TestCase
{
   
    use RefreshDatabase;

    public function test_url_can_be_encoded()
    {
        $response = $this->postJson('/api/encode', ['url' => 'https://example.com']);

        $response->assertStatus(200)
                 ->assertJsonStructure(['short_url']);
    }

    public function test_url_can_be_decoded()
    {
        $shortUrl = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'abc123'
        ]);

        $response = $this->postJson('/api/decode', ['short_url' => 'http://short.est/abc123']);

        $response->assertStatus(200)
                 ->assertJson(['original_url' => 'https://example.com']);
    }

    public function test_decode_fails_for_nonexistent_url()
    {
        $response = $this->postJson('/api/decode', ['short_url' => 'http://short.est/xyz999']);

        $response->assertStatus(404);
    }
}
