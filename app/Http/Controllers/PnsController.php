<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pnsmodel;

class PnsController extends Controller
{
    function index()
    {
        return view('pns.index');
    }

    function tracking()
    {
        return view('pns.tracking');
    }

    function detail() {
        $data = pnsmodel::where('id_usulan', request()->id)->first();
        return view('pns.detail', compact('data'));
    }
}
