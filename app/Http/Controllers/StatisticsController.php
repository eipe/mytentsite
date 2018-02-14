<?php

namespace App\Http\Controllers;


use App\Models\Like;
use App\Models\TentSites;

class StatisticsController extends Controller
{

    /**
     * Get statistics for public purpose
     *
     * @return array
     */
    public function getPublicStatistics()
    {
        return [
            'tentSites' => TentSites::all()->where('approved', 1)->count(),
            'bookmarkedTentSites' => Like::all()->count(),
            'countries' => 1,
            'contributors' => TentSites::all()->where('approved', 1)->groupBy('reported_by')->count(),
        ];
    }

}
