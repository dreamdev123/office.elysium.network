<?php


namespace App\Components\Modules\General\HoldingTank;


use App\Blueprint\Interfaces\Module\ModuleBasicAbstract as BasicContract;
use App\Blueprint\Query\Builder;
use App\Blueprint\Services\UserServices;
use App\Components\Modules\General\HoldingTank\Controllers\HoldingTankController;
use App\Components\Modules\General\HoldingTank\ModuleCore\Eloquents\HoldingTank;
use App\Components\Modules\General\HoldingTank\ModuleCore\Traits\Hooks;
use App\Components\Modules\General\HoldingTank\ModuleCore\Traits\Routes;
use App\Components\Modules\MLMPlan\Binary\ModuleCore\Services\BinaryServices;
use App\Eloquents\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Illuminate\Support\Collection;

/**
 * Class HoldingTankIndex
 * @package App\Components\Modules\General\HoldingTank
 */
class HoldingTankIndex extends BasicContract
{
    use Hooks, Routes;

    /**
     * handle module installations
     *
     * @return void
     */
    function install()
    {
        ModuleCore\Schema\Setup::install();
    }

    /**
     * handle module un-installation
     */
    function uninstall()
    {
        ModuleCore\Schema\Setup::uninstall();
    }

    function bootMethods(UserServices $userServices, BinaryServices $binaryServices)
    {
        schedule('Automatic Placement Schedule', function () use($userServices, $binaryServices) {
            $this->autoPlaceUsers($userServices, $binaryServices);
        });
    }


    /**
     * @return Factory|View|mixed
     */
    function adminConfig()
    {
        $data = [
            'styles' => [
                asset('global/plugins/ladda/ladda-themeless.min.css'),
                asset('global/plugins/select2/css/select2.min.css'),
                asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            ],
            'scripts' => [
                asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
                asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
                asset('global/plugins/ladda/spin.min.js'),
                asset('global/plugins/ladda/ladda.min.js'),
                asset('global/plugins/select2/js/select2.full.min.js'),
            ],
            'moduleId' => $this->moduleId,
            'config' => $this->getModuleData(true),
        ];

        return view('General.HoldingTank.Views.adminConfig', $data);
    }

    /**
     * @param array $data
     * @return HoldingTank|Model
     */
    function addUserToHoldingTank($data = [])
    {
        return HoldingTank::create([
            'user_id' => $data['user']->id,
            'status' => 0
        ]);
    }

    /**
     * @param User $user
     * @return int|mixed
     */
    function getPosition(User $user)
    {
        $position = HoldingTank::where('user_id', $user->id)->first();

        $defaultPosition = 1;
         if ($position) $defaultPosition = (int)$position->default_position;

        switch ($defaultPosition) {
            case 1:
                return 1;
                break;
            case 2:
                return 2;
                break;
            case 3:
                return app()->call([$this, 'getAlternatePosition'], ['user' => $user]);
                break;
            case 4:
                return app()->call([$this, 'getWeakestLeg'], ['user' => $user]);
                break;
        }
    }

    /**
     * @param User $user
     * @param UserServices $userServices
     * @return int
     */
    function getAlternatePosition(User $user, UserServices $userServices)
    {
        $referrals = $userServices->getUsers(collect([]), '', true, ['repoData'])
            ->whereHas('repoData', function ($query) use ($user) {
                /** @var Builder $query */
                $query->where('sponsor_id', $user->id);
            })->orderBy('created_at', 'desc');

        $position = 1;
        if ($referrals->count()) $position = ($referrals->first()->repoData->position == 1) ? 2 : 1;

        return $position;
    }

    /**
     * @param User $user
     * @param BinaryServices $binaryServices
     * @return int
     */
    function getWeakestLeg(User $user, BinaryServices $binaryServices)
    {
        $binaryPoints = $binaryServices->getPoints(collect([
            'userId' => $user ? $user->id : loggedId(),
            'fullStats' => true,
        ]))->first();

        return $binaryPoints->leftpoints >= $binaryPoints->rightpoints ? 2 : 1;
    }


    /**
     * @param UserServices $userServices
     * @param BinaryServices $binaryServices
     */
    function autoPlaceUsers(UserServices $userServices, BinaryServices $binaryServices)
    {
        $affiliate_users = User::where('package_id', '=', 1)->get();
        foreach ($affiliate_users as $user) {
            HoldingTank::updateOrCreate([
                'user_id' => $user->id
            ], [
                'status' => 1
            ]);
        }
        $holding_id = \App\Eloquents\Module::where('slug', 'General-HoldingTank')->first()->id;
        $moduleData = \App\Eloquents\ModuleData::where('module_id', $holding_id)->first()->module_data;
        $tankUsers = $userServices->getUsers(Collect(['orderBy' => 'ASC']), null, true)->whereDoesntHave('repoData')->get();
        $tankUsers->each(function ($user) use ($binaryServices, $moduleData) {
            if (strtotime(date('Y-m-d H:i:s')) - strtotime($user->created_at->addHours($moduleData['holding_time'])->format('Y-m-d H:i:s')) >= 0 ) {
                app()->call([app(HoldingTankController::class), 'autoPlaceUser'], [
                    'userId' => $user->id
                ]);
            }
        });
    }
}
