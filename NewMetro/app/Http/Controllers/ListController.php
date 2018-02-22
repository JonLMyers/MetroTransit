<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListController extends Controller
{
    public function show()
    {
        $characters = [
            'Train 1' => 'Rochester',
            'Train 2' => 'Albany',
            'Train 3' => 'Buffalo',
            'Train 4' => 'Chicago',
            'Train 5' => 'Tampa',
            'Train 6' => 'Detroit',
            'Train 7' => 'New York City',
            'Train 8' => 'Baltimore',
            'Train 9' => 'Washington D.C.',
            'Train 10' => 'Mclean'
        ];

        return view('welcome')->withCharacters($characters);
    }
}
