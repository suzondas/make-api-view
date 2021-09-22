<?php

namespace App\Http\Controllers;

use App\Models\Apis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $apis = Apis::where(['user_id'=>Auth::id()])->with('keys')->get();
        return view('home')->with('apis', $apis);
    }
}
