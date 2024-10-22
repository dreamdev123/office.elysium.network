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

namespace App\Components\Modules\Wallet\TWiseWallet;

use App\Blueprint\Interfaces\Module\WalletModuleAbstract;
use App\Blueprint\Interfaces\Module\WalletModuleInterface;
use App\Blueprint\Services\ModuleServices;
use App\Components\Modules\Wallet\TWiseWallet\Controllers\TWiseWalletController;
use App\Components\Modules\Wallet\TWiseWallet\ModuleCore\Eloquents\TWiseWallet;
use App\Components\Modules\Wallet\TWiseWallet\ModuleCore\Eloquents\User;
use App\Components\Modules\Wallet\TWiseWallet\ModuleCore\Traits\Hooks;
use App\Components\Modules\Wallet\TWiseWallet\ModuleCore\Traits\Routes;
use App\Components\Modules\Wallet\TWiseWallet\ModuleCore\Traits\Validations;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

/**
 * Class TWiseWalletIndex
 * @package App\Components\Modules\Wallet\TWiseWallet
 */
class TWiseWalletIndex extends WalletModuleAbstract implements WalletModuleInterface
{
    use Routes, Hooks, Validations;

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

   

    /**
     * Get E-wallet balance
     *
     * @param \App\Eloquents\User $user
     * @param bool $cached
     * @return mixed
     */
    function getBalance(\App\Eloquents\User $user = null, $cached = true)
    {
        $user = ($user && $user->exists) ? User::find($user->id) : User::find(loggedId());

        if (!$cached) return $user->balance();

        $vaultWallet = collect(vault($user)->get('wallet', []));
        $cachedWallet = collect($vaultWallet->get($slug = Str::camel($this->getRegistry()['key']), []));

        return $cachedWallet->get('balance', function () use ($cachedWallet, $slug, $user, $vaultWallet) {
            return fillVault($user, vault($user)->put('wallet', $vaultWallet->put($slug, [
                'balance' => $this->getBalance($user, false)
            ])))['wallet'][$slug]['balance'];
        });
    }

    /**
     * @param \App\Eloquents\User $user
     * @return mixed
     */
    function updateCache(\App\Eloquents\User $user = null)
    {
        $user = ($user && $user->exists) ? User::find($user->id) : User::find(loggedId());
        $vaultWallet = collect(vault($user)->get('wallet', []));
        $slug = Str::camel($this->registry['key']);

        return fillVault($user, vault($user)->put('wallet', $vaultWallet->put($slug, [
            'balance' => User::find($user->id)->balance()
        ])));
    }

    /**
     * @param array $data
     */
    function addUserToWallet($data = [])
    {
        TWiseWallet::create(['user_id' => $data['user']->id]);
    }

    /**
     * @param \App\Eloquents\User|null $user
     * @param array $options
     * @return Builder|mixed
     */
    function credited(\App\Eloquents\User $user = null, $options = [])
    {
        return (new ModuleCore\Eloquents\User)->find($user->id)->credited($options);
    }

    /**
     * @param \App\Eloquents\User|null $user
     * @param array $options
     * @return Builder|mixed
     */
    function debited(\App\Eloquents\User $user = null, $options = [])
    {
        return (new ModuleCore\Eloquents\User)->find($user->id)->debited($options);
    }


    /**
     * @param Request $request
     * @return string
     */
    function getMemberTransactionList(Request $request)
    {
        return "<div class='ewalletTranList'>" . app()->call([new TWiseWalletController, 'getMemberTransactionList']) . '</div>';
    }
}
