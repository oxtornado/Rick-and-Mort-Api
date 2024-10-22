<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RickMortyController extends Controller
{
    public function getCharacters()
    {
        $client = new Client();

        try {
            $response = $client->get('https://rickandmortyapi.com/api/character', [
                'verify' => false, // Temporarily disable SSL verification
                'timeout' => 10.0,
            ]);
            $characters = json_decode($response->getBody()->getContents(), true);
            return view('characters', ['characters' => $characters['results']]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Log the error message and details
            \Log::error('Error retrieving characters: ' . $e->getMessage());
            if ($e->hasResponse()) {
                \Log::error('Response: ' . $e->getResponse()->getBody()->getContents());
            }
            return response()->json(['error' => 'Unable to retrieve characters.'], 500);
        }
        
    }
}
