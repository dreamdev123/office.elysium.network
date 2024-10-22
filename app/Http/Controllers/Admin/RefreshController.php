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

namespace App\Http\Controllers\Admin;

ini_set('max_execution_time', 1200);
set_time_limit(1200);

use App\Blueprint\Services\UserServices;
use App\Components\Modules\MLMPlan\Binary\ModuleCore\Eloquents\BinaryPoint;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankAchievementHistory;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankUser;
use App\Components\Modules\System\MLM\ModuleCore\Eloquents\PendingDistributionList;
use App\Components\Modules\Wallet\Ewallet\ModuleCore\Eloquents\Ewallet;
use App\Components\Modules\General\HoldingTank\ModuleCore\Eloquents\HoldingTank;
use App\Components\Modules\Payment\TransferWise\ModuleCore\Eloquents\TransferWiseTransaction;
use App\Eloquents\ActivityHistory;
use App\Eloquents\Attachment;
use App\Eloquents\Bookmark;
use App\Eloquents\Commission;
use App\Eloquents\CronHistory;
use App\Eloquents\CronJob;
use App\Eloquents\CustomField;
use App\Eloquents\LeftMenu;
use App\Eloquents\Mail;
use App\Eloquents\MailTransaction;
use App\Eloquents\Module;
use App\Eloquents\ModuleData;
use App\Eloquents\Order;
use App\Eloquents\OrderProduct;
use App\Eloquents\OrderTotal;
use App\Eloquents\TemporaryData;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionCharge;
use App\Eloquents\TransactionDetail;
use App\Eloquents\User;
use App\Eloquents\UserMeta;
use App\Eloquents\UserRepo;
use App\Eloquents\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;
use App\Blueprint\Services\ExternalMailServices;
use Carbon\Carbon;


/**
 * Class systemRefreshController
 * @package App\Http\Controllers\Admin
 */
class RefreshController extends Controller
{

    function testRun($operation)
    {

        switch ($operation) {
            case 'pfc':
                callmodule('Commission-PerformanceFeeCommission', 'process');
                echo 'PFC running finished';
                break;
            case 'distributeFPC':
                callmodule('Commission-FounderPerformanceCommission','process');
                echo 'FPC running finished';
                break;
            case 'dbp':
                callmodule('Commission-DiamondBonusPool', 'process');
                echo 'Diamond Pool Bonus running finished';
                break;
            case 'pfc_pool_bonus':
                callmodule('Commission-StarPFCPoolBonus', 'process');
                echo '5 Start PFC Pool Bonus running finished';
                break;
            case 'rank':
                callmodule('Rank-AdvancedRank', 'process');
                echo 'Ranking calculation finished';
                break;
            case 'userRank':
                callmodule('Rank-AdvancedRank', 'userRankCheck');
                echo 'Ranking calculation finished';
                break;
            case 'tvc':
                callmodule('Commission-TeamVolumeCommissions', 'distributePending');
                echo 'Pending TVC Distributed';
                break;
            case 'gmb':
                callmodule('Commission-GenerationalMatchingBonus', 'distributePending');
                echo 'Pending GMB Distributed';
                break;
            case 'deleteTvc':
                callmodule('Commission-TeamVolumeCommissions','deleteAlreadyDistributed');
                echo 'Finished';
                break;
            case 'distributeMissingTvc':
                callmodule('Commission-TeamVolumeCommissions','distributeMissedCommission');
                echo 'Finished';
                break;
            case 'check_commission':
                $this->checkCommission();
                echo 'Commission Testing';
                break;
            case 'placeHoldingTankUsers':
                callmodule('General-HoldingTank','autoPlaceUsers');
                echo 'Finished';
                break;
            case 'autoDetectXoomUsers':
                callmodule('General-Xoom','autoDetectXoomUsers');
                echo 'Finished';
                break;
            case 'autoSendTellFriend':
                callmodule('General-SoHo','autoSendTellFriend');
                echo 'Finished';
                break;
            case 'resetTables':
                $this->resetTables();
                echo 'Finished';
                break;
            case 'reExecute':
                $this->reExecute();
                echo 'Finished';
                break;
            case 'calculateUplineCycle':
                getModule('Commission-TeamVolumeCommissions')->process([
                    'user' => User::find(828)
                ]);
                echo 'missing TVC Distributed';
                break;
            case 'sendWelcomeMail':
                $externalMailServices = new ExternalMailServices;
                $user = User::where('customer_id', 517399)->first();
                $externalMailServices->sendmailchimp($user);
                echo 'Finished';
                break;
            case 'changeDefaultBinaryPosition':
                $userRepos = UserRepo::where('default_binary_position', '>', 2)->get();
                foreach ($userRepos as $userRepo) {
                    $userRepo->default_binary_position = 1;
                    $userRepo->save();
                }
                $holdingTanks = HoldingTank::where('default_position', '>', 2)->get();
                foreach ($holdingTanks as $holdingTank) {
                    $holdingTank->default_position = 1;
                    $holdingTank->save();
                }
                $affiliate_users = User::where('package_id', '=', 1)->get();
                foreach ($affiliate_users as $user) {
                    HoldingTank::updateOrCreate([
                        'user_id' => $user->id
                    ], [
                        'status' => 1
                    ]);
                }
                echo 'Finished';
                break;
            case 'MEC':
                getModule('Commission-MultiTierEOSCommissions')->process([
                    'user' => User::find(1170),
                    'scope' => 'registration',
                ]);
                echo 'Finished MEC';
                break;
            case 'MIC':
                getModule('Commission-MultiTierInsiderCommissions')->process([
                    'user' => User::find(2554),
                    'scope' => 'registration',
                    'package' => 'annual',
                ]);
                echo 'Finished MIC';
                break;
            case 'Expireuser':
                $data['user'] = User::find(2554);
                defineAction('expireuser', 'expire', collect(['result' => $data]));
                echo 'Finished cv points';
                break;
            // case 'fixTree':
            //     UserRepo::find(2)->rebuildSubtree('placement');
            //     UserRepo::find(2)->rebuildSubtree('sponsor');
            //     echo 'Fix tree done';
            //     break;
        }
    }

    /**
     * @param $projectUrl
     */
    function leftMenuCorrection($projectUrl)
    {
        foreach (LeftMenu::get() as $menu) {
            $updatedLink = str_replace("http://localhost/elysium/app/public/", $projectUrl, $menu->link);
            LeftMenu::find($menu->id)->update([
                'link' => $updatedLink
            ]);
        }
    }

    function checkCommission()
    {
        $userId = 6;
        $orderId = 2336;
        defineAction('postRegistration', 'registration', collect(['result' => [
            'user' => User::find($userId),
            'orderId' => $orderId
        ]]));
    }

    function reExecute(){
        $userServices = app(UserServices::class);
        $users = $userServices->getUsers(Collect(['orderBy' => 'ASC']), null, true)
            ->where('id', '!=', 2)->where('recalculate', 0)->limit(100)->get();

        $users->each(function ($user){
            $data = [
                'user' => $user = User::find($user->id)
            ];
            defineAction('postRegistration', 'registration', collect(['result' => $data]));
            $user->update([
                'recalculate' => true
            ]);
        });
    }

    function resetTables(){
        Schema::disableForeignKeyConstraints();
        DB::transaction(function (){
            AdvancedRankAchievementHistory::truncate();
            AdvancedRankUser::truncate();
            Commission::truncate();
            CronHistory::truncate();
            Ewallet::truncate();
            PendingDistributionList::truncate();
            TransactionDetail::truncate();
            Transaction::truncate();
            BinaryPoint::truncate();
        });
    }


    /**
     * @param bool $force
     * @return void
     * @throws Throwable
     */
    protected function systemRefresh($force = false)
    {
        getModule('Commission-FastTrackCommissions')->process(['user' => User::find(2584)]);
        dd('success');
        /*$user = User::find(901);
        $rightChildrens = $user->repoData->rightChildrens();
        $totalPoint = 0;
        $rightChildrens->each(function ($children) use(&$totalPoint){
            $totalPoint += getPackageInfo($children->package_id)->pv;
        });

        dd($totalPoint); */
        DB::beginTransaction();
        //DB::transaction(function () use ($force) {
        // $this->dataTruncate($force);

        //$this->dataSeeding($force);

        //$projectUrl = 'http://elysium.hybridmlmsoftware.com/';
        // $this->leftMenuCorrection($projectUrl);
        // });

        DB::rollBack();
    }

    /**
     * @param $force
     * @return bool
     */
    protected function dataTruncate($force)
    {
        defineFilter('dataTruncate', null, 'systemRefresh', ['forceTruncate' => $force]);
        Schema::disableForeignKeyConstraints();

        User::truncate();
        UserRepo::truncate();
        UserMeta::truncate();
        UserRole::truncate();
        ActivityHistory::truncate();
        Mail::truncate();
        MailTransaction::truncate();
        Commission::truncate();
        Order::truncate();
        OrderProduct::truncate();
        OrderTotal::truncate();
        Transaction::truncate();
        TransactionCharge::truncate();
        TransactionDetail::truncate();
        TransactionDetail::truncate();
        TemporaryData::truncate();
        CronHistory::truncate();
        Attachment::truncate();

        if ($force) {
            Bookmark::truncate();
            Module::truncate();
            ModuleData::truncate();
            CronJob::truncate();
            CustomField::truncate();
        }
        echo 'System databases truncated';
    }

    /**
     * dataSeeding
     * @param $force
     * @return bool
     */
    protected function dataSeeding($force)
    {
        defineFilter('dataTruncate', null, 'systemRefresh', ['forceTruncate' => $force]);
        User::insert([
            ["id" => 1, "username" => "company", "customer_id" => "1", "email" => "info@hybridmlm.com", "password" => bcrypt('hybridmlm'), "phone" => 123456789, 'sponsor_id' => 0, 'package_id' => 0, 'is_confirmed' => 1],
            ["id" => 2, "username" => "admin", "customer_id" => "2", "email" => "admin@email.com", "password" => bcrypt('admin'), "phone" => 123456789, 'sponsor_id' => 0, 'package_id' => 0, 'is_confirmed' => 1]
        ]);

        UserMeta::insert([
            ["user_id" => 1, "firstname" => "Hybrid", "lastname" => 'MLM', "dob" => '2017-05-10', "address" => '', "country_id" => 101, "state_id" => 19, "city_id" => 1892, "gender" => 'M'],
            ["user_id" => 2, "firstname" => "System", "
            lastname" => 'Admin', "dob" => '2017-05-10', "address" => 'admin', "country_id" => 101, "state_id" => 19, "city_id" => 1892, "gender" => 'M']
        ]);

        UserRepo::insert([
            ["user_id" => 1, "Sponsor_id" => 0, "parent_id" => 0, "LHS" => 0, "RHS" => 0, "SLHS" => 0, "SRHS" => 0, "position" => 0],
            ["user_id" => 2, "Sponsor_id" => 0, "parent_id" => 0, "LHS" => 1, "RHS" => 2, "SLHS" => 1, "SRHS" => 2, "position" => 0]
        ]);

        UserRole::insert([
            ["user_id" => 1, "type_id" => 1, "sub_type_id" => 1],
            ["user_id" => 2, "type_id" => 2, "sub_type_id" => 2]
        ]);

        defineFilter('dataSeeding', [], 'systemRefresh', ['forceRefresh' => $force]);
        echo 'Database seeding completed success full';
    }
}