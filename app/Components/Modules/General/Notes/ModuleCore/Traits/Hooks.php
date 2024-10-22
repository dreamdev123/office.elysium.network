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

namespace App\Components\Modules\General\Notes\ModuleCore\Traits;

use App\Blueprint\Services\MenuServices;
use App\Blueprint\Services\UserServices;
use App\Eloquents\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Trait Hooks
 * @package App\Components\Modules\General\Notes\ModuleCore\Traits
 */
trait Hooks
{
    /**
     * @return mixed
     */
    function hooks()
    {
        return app()->call([$this, 'notesHooks']);
    }

    /**
     * Register hooks
     *
     * @param Request $request
     * @param UserServices $userServices
     * @return Void
     */
    public function notesHooks(Request $request, UserServices $userServices, MenuServices $menuServices)
    {

        registerFilter('memberManagement', function ($content) {
            return $content .= "<li data-target='notes_comments'>
             <i class='fa fa-user' aria-hidden='true'></i>
                <p>" . _mt('General-Notes', 'Notes.Notes') . "</p>
                
            </li>";
        }, 'nav-note');

        registerFilter('memberManagement', function ($content) {
            return $content .= '<form class="panelForm" data-unit="notes_comments" data-target="notes_comments"></form>';
        }, 'slice');

        registerFilter('memberManagement', function ($content, $action) use ($userServices, $request) {
            if ($action == 'notes_comments') {
                $data = [
                    'user' => User::with('repoData.parentUser.repoData', 'repoData.sponsorUser.repoData')
                        ->find($request->input('user')),
                ];
                $data['downlines'] = $userServices->getUsers(collect([]), '', true)
                    ->whereHas('repoData', function ($query) use ($request) {
                        /** @var Builder $query */
                        $query->where('sponsor_id', $request->input('user'));
                    })->orderBy('created_at', 'desc')->get();
                $options = collect([
                    'fromDate' => Carbon::now()->toDateString() . ' 00:00:00',
                    'toDate' => Carbon::now()->toDateTimeString(),
                ]);
                $data['todayJoined'] = $userServices->getUsers($options, '', true)
                    ->whereHas('repoData', function ($query) use ($request) {
                        /** @var Builder $query */
                        $query->where('sponsor_id', $request->input('user'));
                    })->count();

                $options = collect([
                    'fromDate' => Carbon::now()->startOfMonth(),
                    'toDate' => Carbon::now()->endOfMonth(),
                ]);
                $data['thisMonthJoined'] = $userServices->getUsers($options, '', true)
                    ->whereHas('repoData', function ($query) use ($request) {
                        /** @var Builder $query */
                        $query->where('sponsor_id', $request->input('user'));
                    })->count();
                $options = collect([
                    'fromDate' => Carbon::now()->startOfYear(),
                    'toDate' => Carbon::now()->endOfYear(),
                ]);
                $data['thisYearJoined'] = $userServices->getUsers($options, '', true)
                    ->whereHas('repoData', function ($query) use ($request) {
                        /** @var Builder $query */
                        $query->where('sponsor_id', $request->input('user'));
                    })->count();

                return view('General.Notes.Views.index', $data);

            } else {
                return $content;
            }
        }, 'unitFilter');
    }
}
