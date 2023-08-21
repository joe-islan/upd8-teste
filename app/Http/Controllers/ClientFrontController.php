<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientFrontController extends Controller
{
    public function index()
    {
        return view('client.index');
    }

    public function create()
    {
        return view('client.create');
    }

    public function edit($id)
    {
        $client = Client::find($id);
        return view('client.edit')->with('client', $client);
    }

    public function show($id)
    {
        $client = Client::find($id);
        return view('client.show')->with('client', $client);
    }
}
