<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstagramAuthControllerApi extends Controller
{
    public function __construct()
    {
        $this->client_id = "359294529284647";
        $this->client_secret = "d43b1fce076bb4c522f70de3cb7a1ab5";
        $this->redirect_uri = "https://192.168.0.4:3000/handle-code";
        $this->scope =
            'user_profile,user_media,public_content,follower_list,instagram_graph_user_media,instagram_graph_user_profile';

        $this->ig_uri_access_token = 'https://api.instagram.com/oauth/access_token';
        $this->ig_uri_authorize = 'https://api.instagram.com/oauth/authorize?client_id='
            . $this->client_id .
            '&redirect_uri=' . $this->redirect_uri .
            '&scope=' . $this->scope .
            '&response_type=code';
    }

    function authorizeCode()
    {
        return redirect($this->ig_uri_authorize);
    }

    function authorizeToken(Request $request)
    {
        $code = $request->get('code');

        $data = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri,
            'code' => $code
        );

        $res = Http::asForm()->post($this->ig_uri_access_token, $data);

        if (!$res->ok()) {
            return $res->getBody();
            response($res)->status(400);
        }

        return response($res->body());
    }
}
