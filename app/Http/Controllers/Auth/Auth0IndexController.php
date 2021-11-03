<?php

namespace App\Http\Controllers\Auth;

use App;
use Auth;
use Auth0\SDK\Auth0;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class Auth0IndexController extends Controller
{
    public function login(Request $request){
        if (Auth::check()) {
            return redirect()->back();
        }

        session(['auth_return_to_url' => url()->previous()]);
        return App::make('auth0')->login(
            null,
            null,
            ['scope' => 'openid name profile'],
            'code'
        );
    }
    public function logout(){
        Auth::logout();

        $logoutUrl = sprintf(
            'https://%s/v2/logout?client_id=%s&returnTo=%s',
            config('laravel-auth0.domain'),
            config('laravel-auth0.client_id'),
            config('app.url')
        );

        return redirect()->intended($logoutUrl);
    }
    public function profile(){

    }
}
