<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UnsplashControllerApi extends Controller
{
    public function __construct()
    {
        $this->access_key = 'T9kMw4PuQfM5thrO2n8pFx5a47thY9wkgbJeNM479g8';
        $this->secret = '5iw6p4sm0xOFPMoW4RhyTznRovcHi2O6gdfUrEe61DI';

        $this->base_uri = 'https://api.unsplash.com';
        $this->base_uri_photos = $this->base_uri . '/photos' . '?client_id=' . $this->access_key;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Http::get($this->base_uri_photos);
        return response($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
