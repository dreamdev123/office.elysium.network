<?php
/**
 *  -------------------------------------------------
 *  Hybrid MLM  Copyright (c) 2018 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Acemero Technologies Pvt Ltd
 * @link https://www.acemero.com
 * @see https://www.hybridmlm.io
 * @version 1.00
 * @api Laravel 5.4
 */

/**
 * Created by PhpStorm.
 * User: Muhammed Fayis
 * Date: 1/11/2018
 * Time: 12:06 AM
 */

namespace App\Components\Modules\General\Notes\ModuleCore\Services;

use App\Eloquents\UserRepo;
use App\Http\Controllers\Admin\IndexController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


/**
 * Class ReferralListServices
 * @package App\Components\Modules\General\Notes\ModuleCore\Services
 */
class NotesServices
{
    /**
     * @param $userID
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */

    /**
     * @return array
     */
    public function getMonthlyReferralListGraphData($userId)
    {
        /** @var IndexController $controller */
        $controller = app(IndexController::class);
        $users = UserRepo::with('user')->where('sponsor_id', $userId)
            ->whereHas('user', function ($query) {
                $query->whereBetween('created_at', [Carbon::today()->subMonth(11)->startOfMonth()->format('Y-m-d'), Carbon::today()->format('Y-m-d')]);
            })
            ->groupBy(DB::raw('month(created_at)'))->selectRaw('*, count(*) referralCount, month(created_at) month')
            ->orderByDesc('created_at', 'desc')->get();
        $graph = $controller->prepareShortGraphData($users, 'month', 'referralCount');
        $formattedGraph = $controller->formatToArrayForGraph($graph);
        $xAxises = $graph->keys();

        return compact('formattedGraph', 'xAxises');
    }
}
