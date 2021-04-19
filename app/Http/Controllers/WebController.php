<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function main() {
        return view('home');
    }

    public function index($path) {

        $is_auth = \Auth::user();

        if($is_auth) {
            \Log::info($is_auth);
            return view('home');
        }

        return redirect('/');
    }
}
