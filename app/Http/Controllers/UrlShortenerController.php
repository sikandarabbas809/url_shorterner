<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class UrlShortenerController extends Controller
{
    protected $baseUrl = 'http://short.est/';

    public function encode(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        $existing = ShortUrl::where('original_url', $request->url)->first();
        if ($existing) {
            return response()->json(['short_url' => $this->baseUrl . $existing->short_code]);
        }

        $shortCode = Str::random(6);
        ShortUrl::create(['original_url' => $request->url, 'short_code' => $shortCode]);

        return response()->json(['short_url' => $this->baseUrl . $shortCode]);
    }

    public function decode(Request $request)
    {
        $request->validate(['short_url' => 'required|string']);

        $shortCode = str_replace($this->baseUrl, '', $request->short_url);
        $urlEntry = ShortUrl::where('short_code', $shortCode)->first();

        if (!$urlEntry) {
            return response()->json(['error' => 'URL not found'], 404);
        }

        return response()->json(['original_url' => $urlEntry->original_url]);
    }
}
