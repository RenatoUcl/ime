<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndiceMultiController extends Controller
{
    public function index()
    {
        return view('indice.index');
    }
}
