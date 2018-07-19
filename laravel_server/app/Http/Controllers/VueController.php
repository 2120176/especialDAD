<?php

namespace App\Http\Controllers;

class VueController extends Controller
{
    public function index()
    {
        return view('vue.index');
    }

    public function fbpage()
    {
        return view('fblogin');
    }
}
