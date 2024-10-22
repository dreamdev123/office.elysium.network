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

namespace App\Components\Modules\General\ClientList\ModuleCore\Traits;

use App\Blueprint\Services\MenuServices;
use App\Blueprint\Services\UserServices;
use App\Eloquents\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Trait Hooks
 * @package App\Components\Modules\General\ClientList\ModuleCore\Traits
 */
trait Hooks
{
    /**
     * @return mixed
     */
    function hooks()
    {
        return app()->call([$this, 'clientListHooks']);
    }

    /**
     * Register hooks
     *
     * @param Request $request
     * @param UserServices $userServices
     * @return Void
     */
    public function clientListHooks(Request $request, UserServices $userServices, MenuServices $menuServices)
    {
//        registerFilter('leftMenus', function ($menus) use ($menuServices) {
//            /** @var Collection $menus */
//            return $menus->merge($menuServices->menusFromRaw([
//                [
//                    'label' => ['en' => 'Referral List', 'es' => 'Lista de referencias', 'ru' => 'Список рефералов', 'ko' => '추천 목록', 'pt' => 'Lista de referências', 'ja' => '紹介リスト', 'zh' => '推荐清单', 'vi' => 'Danh sách giới thiệu', 'sw' => 'Orodha ya Rufaa', 'hi' => 'रेफरल सूची', 'fr' => 'Liste de référence', 'it' => 'Elenco dei referral'],
//                    'link' => '',
//                    'route' => '',
//                    'route_name' => 'user.clientList.myReferral',
//                    'icon_image' => '',
//                    'icon_font' => 'fa fa-user',
//                    'parent_id' => 'network',
//                    'permit' => ['user:defaultUser'],
//                    'sort_order' => '4',
//                    'protected' => '0',
//                    'description' => '',
//                    'attributes' => '',
//                ]
//            ]));
//        });

        registerFilter('memberManagement', function ($content) {
            return $content .= "<li data-target='client_list'>
             <i class='fa fa-user' aria-hidden='true'></i>
                <p>" . _mt('General-ClientList', 'ClientList.Client_List') . "</p>
                
            </li>";
        }, 'nav-client');

        registerFilter('memberManagement', function ($content) {
            return $content .= '<form class="panelForm" data-unit="client_list" data-target="client_list"></form>';
        }, 'slice');

        registerFilter('memberManagement', function ($content, $action) use ($userServices, $request) {
            if ($action == 'client_list') {
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

                return view('General.ClientList.Views.index', $data);

            } else {
                return $content;
            }
        }, 'unitFilter');
    }
}
