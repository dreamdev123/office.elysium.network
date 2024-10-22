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

namespace App\Components\Modules\General\BinaryPointTransaction\Controllers;

use App\Components\Modules\General\BinaryPointTransaction\ModuleCore\Services\BinaryServices;
use App\Components\Modules\MLMPlan\Binary\ModuleCore\Eloquents\BinaryPoint;
use App\Eloquents\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class BinaryPointTransactionController
 * @package App\Components\Modules\General\BinaryPointTransaction\Controllers
 */
class BinaryPointTransactionController
{

    /**
     * @param Request $request
     */
    function saveBinaryPoint(Request $request, BinaryServices $binaryServices)
    {
        // BinaryPoint::create([
        //     'user_id' => $request->input('user_id'),
        //     'point' => $request->input('point'),
        //     'position' => $request->input('position'),
        //     'is_credit' => 1,
        //     'pair' => 0,
        //     'possible_pair' => 0,
        //     'from_user' => 2,
        //     'context' => 'manual'
        // ]);

        $user = User::find($request->input('user_id'));
        DB::transaction(function () use ($user, $binaryServices, $request) {
            BinaryPoint::create([
                'user_id' => $user->id,
                'point' => $request->input('point'),
                'position' => $request->input('position'),
                'is_credit' => 1,
                'pair' => 0,
                'possible_pair' => 0,
                'from_user' => 2,
                'context' => 'manual'
            ]);

            if ($request->input('distribute_to_upline')) {
                $upLineUsers = $binaryServices->getAncestorsOf($user);
                $position = $user->repoData->position;
                foreach ($upLineUsers as $user) {
                    BinaryPoint::create([
                        'user_id' => $user->user_id,
                        'point' => $request->input('point'),
                        'position' => $position,
                        'is_credit' => 1,
                        'pair' => 0,
                        'possible_pair' => 0,
                        'from_user' => 2,
                        'context' => 'manual'
                    ]);
                    $position = $user->position;
                }
            }
        });
    }
}