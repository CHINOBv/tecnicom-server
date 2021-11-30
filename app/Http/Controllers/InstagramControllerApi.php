<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class InstagramControllerApi extends Controller
{

    public function __construct()
    {
        $this->instagram_uri = 'https://graph.instagram.com/';
        $this->instagram_fields = 'id,username,media_type,media_url,permalink,thumbnail_url,timestamp,caption';
        $this->client_id = "359294529284647";
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user_id = $request->query->get('user_id');
        $access_token = $request->query->get('access_token');
        $res = Http::get($this->instagram_uri . $user_id . '/media?fields=' . $this->instagram_fields . '&access_token=' . $access_token);
        return response($res);
    }

    public function show($id)
    {

        $validator = Validator::make(request()->all(), [
            'access_token' => 'required',
            'media_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // $media_id = request()->query->get('media_id');
        $type = request()->query->get('media_type');
        $access_token = request()->query->get('access_token');

        $uri = $this->instagram_uri . $id . '?&access_token=' . $access_token;
        if ($type === "CAROUSEL_ALBUM") {
            $uri = $uri . '&fields=' . $this->instagram_fields . ',children';
        } elseif ($type === "CAROUSEL_PHOTO") {
            $uri = $uri . '&fields=id,username,media_type,media_url,permalink,thumbnail_url,timestamp';
        } else {
            $uri = $uri . '&fields=' . $this->instagram_fields;
        }


        $res = Http::get($uri);
        return response($res);
    }
}
