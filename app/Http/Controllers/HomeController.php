<?php

namespace App\Http\Controllers;

use App\Helpers\Fundawande;
use App\Resource;
use Illuminate\Http\Request;
use App\Providers\StorageServiceProvider;

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
        $storage_percentage = Fundawande::getTotalStorage();

        return view(
            'welcome',
            [
                'storage_percentage' => $storage_percentage,
            ]
        );
    }
}
