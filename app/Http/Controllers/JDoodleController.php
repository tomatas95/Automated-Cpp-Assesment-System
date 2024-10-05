<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class JDoodleController extends Controller
{
    public function execute(Request $request)
    {
        // 3d105f367dc8b001a86a06c22a82da31
        // 3703317ffba10cff181b344945957d13e4dc6cb28a46b27689457cfc3fff3cc6
        $clientId = '3d105f367dc8b001a86a06c22a82da31';
        $clientSecret = '3703317ffba10cff181b344945957d13e4dc6cb28a46b27689457cfc3fff3cc6';

        $client = new Client();
        $url = 'https://api.jdoodle.com/v1/execute';

        $requestData = $request->all();

        try {
            $response = $client->post($url, [
                'json' => [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'script' => $requestData['script'],
                    'language' => $requestData['language'],
                    'versionIndex' => $requestData['versionIndex'],
                    'stdin' => $requestData['stdin']
                ]
            ]);

            return response()->json(json_decode($response->getBody()->getContents(), true));

        } catch (\Exception $e) {
            return response()->json(['error' => 'err'], 500);
        }
    }
}
