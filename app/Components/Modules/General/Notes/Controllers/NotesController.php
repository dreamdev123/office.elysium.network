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

namespace App\Components\Modules\General\Notes\Controllers;

use App\Blueprint\Services\UserServices;
use App\Components\Modules\General\Notes\ModuleCore\Services\NotesServices;
use App\Components\Modules\General\Notes\NotesIndex as Module;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRank;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankAchievementHistory;
use App\Eloquents\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ReferralListController
 * @package App\Components\Modules\General\ReferralList\Controllers
 */
class NotesController extends Controller
{
    /**
     * __construct function
     */
    function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }

    /**
     * @param Request $request
     * @return mixed
     */

    /**
     * @param Request $request
     * @param NotesServices $notesServices
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function get_notes(Request $request)
    {
        $userID = $request->input('id');
        $user = User::find($userID);
        $data = $user->notes;
        return  $data;
    }

    function save_notes(Request $request)
    {
        $userID = $request->input('id');
        $user = User::find($userID);
        $user->notes = $request->input('notes');
        $user->save();
        $data = array('success'=>true);
        return  $data;
    }

}
