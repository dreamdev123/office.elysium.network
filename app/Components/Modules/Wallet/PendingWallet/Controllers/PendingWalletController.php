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

namespace App\Components\Modules\Wallet\PendingWallet\Controllers;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract;
use App\Blueprint\Interfaces\Module\WalletModuleInterface;
use App\Blueprint\Services\ModuleServices;
use App\Blueprint\Services\TransactionServices;
use App\Blueprint\Traits\Graph\DateTimeFormatter;
use App\Blueprint\Traits\Graph\GraphHelper;
use App\Components\Modules\Wallet\PendingWallet\ModuleCore\Eloquents\User;
use App\Components\Modules\Wallet\PendingWallet\PendingWalletIndex;
use App\Components\Modules\Wallet\PendingWallet\PendingWalletIndex as Module;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionOperation;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class PendingWalletController
 * @package App\Components\Modules\Wallet\PendingWallet\Controllers
 */
class PendingWalletController extends Controller
{
    use GraphHelper, DateTimeFormatter;

    /**
     * @var Application|PendingWalletIndex
     */
    protected $module;

    /**
     * __construct function
     */
    function __construct()
    {
        parent::__construct();

        $this->module = app(Module::class);
    }

    /**
     * index function
     *
     * @return Factory|View
     */
    function index()
    {
        $data = [
            'scripts' => [
                asset('global/plugins/select2/js/select2.full.min.js'),
                asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
                asset('global/plugins/countUpJS/dist/countUp.js'),
            ],
            'styles' => [
                asset('global/plugins/select2/css/select2.min.css'),
                asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
                asset('global/plugins/select2/css/select2-bootstrap.min.css'),
                $this->getModule()->getCssPath('style.css'),
            ],
            'title' => _mt($this->module->moduleId, 'Ewallet.E-Wallet'),
            'heading_text' => _mt($this->module->moduleId, 'Ewallet.E-Wallet'),
            'breadcrumbs' => [
                _t('index.home') => 'admin.home',
                _mt($this->module->moduleId, 'Ewallet.Wallets') => getScope() . '.ewallet',
                _mt($this->module->moduleId, 'Ewallet.E-Wallet') => getScope() . '.ewallet'
            ],
            'wallet' => [],
            'balance' => $this->module->getBalance(),
            'moduleId' => $this->module->moduleId,
            'walletConfig' => getModule($this->module->moduleId)->getModuleData(true)
        ];

        return view('Wallet.PendingWallet.Views.index', $data);
    }

    /**
     * @return Application|ModuleBasicAbstract|WalletModuleInterface
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Request Ewallet units
     *
     * @param Request $request
     * @return JsonResponse
     */
    function requestUnit(Request $request)
    {
        $unitMethod = $request->get('unit');
        $args = $request->input('args') ?: [];

        if ($unitMethod && ($method = 'get' . ucfirst($unitMethod)))
            return app()->call([$this, $method], $args);

        return response()->json(['status' => false, 'message' => _mt($this->module->moduleId, 'Ewallet.The_action_is_not_allowed')]);
    }

    /**
     * @return Factory|View
     */
    function getOverView()
    {
        $data = [
            'scripts' => [],
            'moduleId' => $this->module->moduleId
        ];

        return view('Wallet.PendingWallet.Views.Partials.overView', $data);
    }

    /**
     * @param ModuleServices $moduleServices
     * @return mixed
     */
    function initPayment(ModuleServices $moduleServices)
    {
        return $moduleServices->callModule('Payment-PendingWallet')->renderView();
    }

    /**
     * @param Request $request
     * @return bool
     */
    function hasSufficientBalance(Request $request)
    {
        $totalAmount = 0;

        foreach ($request->input('payee.*') as $id => $payee) {
            $payee['wallet'] = $this->module->moduleId;
            $totalAmount += $payee['amount'] + array_sum(array_column($charges = defineFilter('PreWalletTransaction', [], 'transactionTotal', $payee), 'amount'));
        }

        if ($this->module->getBalance(User::find(loggedId()), false) >= $totalAmount)
            return true;

        return false;
    }

    /**
     * get transaction list
     *
     * @param TransactionServices $transactionServices
     * @param Request $request
     * @return Factory|View
     */
    function getTransactionList(TransactionServices $transactionServices, Request $request)
    {
        $fromDate = loggedUser()->transactions()->first() ? loggedUser()->transactions()->first()->created_at->toDateString() : Carbon::now();
        $args = array_merge([
            'user' => loggedId(),
            'wallet' => $this->module->moduleId,
        ], $request->input('filters', []));
        $data['filters'] = [
            'operation' => [
                'values' => TransactionOperation::all(),
                'default' => ''
            ],
            'type' => [
                'values' => ['income', 'expense'],
                'default' => ''
            ],
            'fromDate' => [
                'default' => $fromDate,
            ],
            'toDate' => [
                'default' => date('Y-m-d'),
            ],
            'groupBy' => [
                'values' => ['Day', 'Month', 'Year'],
                'default' => 'month'
            ],
            'sortBy' => [
                'values' => [
                    'created_at' => 'Date',
                    'transactions.id' => 'TXN ID',
                    'transactions.amount' => 'Amount',
                ],
                'default' => 'created_at'
            ],
            'orderBy' => [
                'values' => ['asc', 'desc'],
                'default' => 'desc'
            ],
        ];
        //Preparing default filter data for view
        foreach ($data['filters'] as $key => $value)
            $data['filters'][$key]['default'] = $request->input("filters.$key", $value['default']);

        $transactions = $transactionServices->getTransactions(collect($args)->except(['groupBy']))
            ->where(function ($query) {
                /** @var Builder $query */
                $query->whereHas('operation', function ($query) {
                    /** @var Builder $query */
                    $query->where('from_module', $this->module->moduleId)->where('payer', loggedId());
                })->orWhereHas('operation', function ($query) {
                    /** @var Builder $query */
                    $query->where('to_module', $this->module->moduleId)->where('payee', loggedId());
                });
            })->get()->toBase();

        $data['groupOfTransaction'] = $transactions->map(function ($value) use ($transactionServices) {
            /** @var Transaction $value */
            return $transactionServices->bindExtraAttributes($value, $this->module);
        })->groupBy(function ($value) use ($data) {
            return $this->formatToGroupBy($value, $data['filters']['groupBy']['default']);
        });
        $data['moduleId'] = $this->module->moduleId;

        return view('Wallet.PendingWallet.Views.Partials.transactionList', $data);
    }

    /**
     * @param Model $model
     * @param $groupBy
     * @return string
     */
    function formatToGroupBy(Model $model, $groupBy)
    {
        /** @var Carbon $createdAt */
        $createdAt = $model->created_at;

        switch ($groupBy) {
            case 'year':
                return $createdAt->format('Y');
                break;
            case 'month':
                return $createdAt->format('F');
                break;
            default:
                return $createdAt->format('l');
                break;
        }
    }

    /**
     * @param TransactionServices $transactionServices
     * @param Request $request
     * @param null $user
     * @return Factory|View
     */
    function getMemberTransactionList(TransactionServices $transactionServices, Request $request, $user = null)
    {
        if ($request->input('user_id'))
            $user = \App\Eloquents\User::find($request->input('user_id'));


        $data ['userId'] = $user ? $user->id : loggedId();

        $user = $user ?: loggedUser();
        $fromDate = $user->transactions()->first() ? $user->transactions()->first()->created_at->toDateString() : Carbon::now();

        $args = array_merge([
            'user' => $user->id,
            'wallet' => $this->module->moduleId,
        ], $request->input('filters', []));

        $data['filters'] = [
            'operation' => [
                'values' => TransactionOperation::all(),
                'default' => ''
            ],
            'type' => [
                'values' => ['income', 'expense'],
                'default' => ''
            ],
            'fromDate' => [
                'default' => $fromDate,
            ],
            'toDate' => [
                'default' => date('Y-m-d'),
            ],
            'groupBy' => [
                'values' => ['Day', 'Month', 'Year'],
                'default' => 'month'
            ],
            'sortBy' => [
                'values' => [
                    'created_at' => 'Date',
                    'transactions.id' => 'TXN ID',
                    'transactions.amount' => 'Amount',
                ],
                'default' => 'created_at'
            ],
            'orderBy' => [
                'values' => ['asc', 'desc'],
                'default' => 'desc'
            ],
        ];
        //Preparing default filter data for view
        foreach ($data['filters'] as $key => $value)
            $data['filters'][$key]['default'] = $request->input("filters.$key", $value['default']);

        $transactions = $transactionServices->getTransactions(collect($args)->except(['groupBy']))
            ->where(function ($query) use ($user) {
                /** @var Builder $query */
                $query->whereHas('operation', function ($query) use ($user) {
                    /** @var Builder $query */
                    $query->where('from_module', $this->module->moduleId)->where('payer', $user->id);
                })->orWhereHas('operation', function ($query) use ($user) {
                    /** @var Builder $query */
                    $query->where('to_module', $this->module->moduleId)->where('payee', $user->id);
                });
            })->get()->toBase();

        $data['groupOfTransaction'] = $transactions->map(function ($value) use ($transactionServices) {
            /** @var Transaction $value */
            return $transactionServices->bindExtraAttributes($value, $this->module);
        })->groupBy(function ($value) use ($data) {
            return $this->formatToGroupBy($value, $data['filters']['groupBy']['default']);
        });
        $data['moduleId'] = $this->module->moduleId;
        $data['user_id'] = $user->id;

        return view('Wallet.PendingWallet.Views.Partials.memberTransactionList', $data);
    }

}
