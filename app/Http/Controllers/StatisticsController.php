<?php

namespace App\Http\Controllers;


class StatisticsController extends Controller
{

    /**
     * Get statistics for public purpose
     *
     * @return array
     */
    public function getPublicStatistics()
    {
        $objTentSitesController = new TentSitesController();
        return [
            'tentSites' => $objTentSitesController->getApprovedCount(),
            'bookmarkedTentSites' => 0,
            'countries' => 1,
            'contributors' => $objTentSitesController->getContributorCount(),
        ];
    }

}
