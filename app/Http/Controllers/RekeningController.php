<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RekeningController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('Produsen/Rekening/Index');
    }
}
