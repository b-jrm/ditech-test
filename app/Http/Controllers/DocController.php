<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DocController extends Controller
{
    public function home(){
        return view('docs');
    }
}
