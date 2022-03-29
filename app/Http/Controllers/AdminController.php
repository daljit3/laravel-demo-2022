<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        /*
        $filename_html = config('glide.filename_data_html');
        if(Storage::exists($filename_html)) {
            $html_size = Storage::size($filename_html);
            $html_time = Storage::lastModified($filename_html);
        } else {
            $html_size = "";
            $html_time = "";
        } */
        $html_size = "";
        $html_time = "";

        return view('admin.home', compact(
            'html_size',
            'html_time')
        );
    }
}
