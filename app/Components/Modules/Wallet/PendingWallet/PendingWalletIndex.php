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

namespace App\Components\Modules\Wallet\PendingWallet;

use App\Blueprint\Interfaces\Module\WalletModuleAbstract;
use App\Blueprint\Interfaces\Module\WalletModuleInterface;
use App\Components\Modules\Wallet\PendingWallet\Controllers\PendingWalletController;
use App\Components\Modules\Wallet\PendingWallet\ModuleCore\Eloquents\User;
use App\Components\Modules\Wallet\PendingWallet\ModuleCore\Traits\Hooks;
use App\Components\Modules\Wallet\PendingWallet\ModuleCore\Traits\Routes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class PendingWalletIndex
 * @package App\Components\Modules\Wallet\Ewallet
 */
class PendingWalletIndex extends WalletModuleAbstract implements WalletModuleInterface
{
    use Routes, Hooks;

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
        return "<div class='ewalletTranList'>" . app()->call([new PendingWalletController, 'getMemberTransactionList']) . '</div>';
    }
}
