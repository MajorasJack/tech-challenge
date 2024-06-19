<?php

namespace App\Http\Controllers;

use App\Client;

class JournalsController extends Controller
{
    public function index(Client $client)
    {
        return response()->json($client->journals);
    }
}
