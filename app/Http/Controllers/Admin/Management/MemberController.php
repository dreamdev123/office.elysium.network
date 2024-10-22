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

namespace App\Http\Controllers\Admin\Management;

use App\Blueprint\Interfaces\Module\CommissionModuleInterface;
use App\Blueprint\Interfaces\Module\ModuleBasicAbstract;
use App\Blueprint\Interfaces\Module\WalletModuleInterface;
use App\Blueprint\Services\ModuleServices;
use App\Blueprint\Services\TransactionServices;
use App\Blueprint\Services\UserServices;
use App\Blueprint\Services\UtilityServices;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRank;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankAchievementHistory;
use App\Eloquents\Package;
use App\Eloquents\User;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\CapitalUser;
use App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents\InsiderUser;
use App\Eloquents\Country;
use App\Eloquents\City;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Components\Modules\Payment\TransferWise\ModuleCore\Eloquents\TransferWiseTransaction;
use App\Components\Modules\Payment\B2BPay\ModuleCore\Eloquents\B2BPayTransaction;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankUser;
use Illuminate\Support\Facades\DB;
use App\Components\Modules\Commission\PerformanceFeeCommission\ModuleCore\Eloquents\InvestmentClient;

/**
 * Class MemberController
 * @package App\Http\Controllers\Admin\Management
 */
class MemberController extends Controller
{
    /**
     * @param User|null $user
     * @return Factory|View
     */
    function index(User $user = null)
    {
        $data = [
            'user' => $user ?: loggedUser(),
            'scripts' => [
                asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
                asset('global/plugins/select2/js/select2.full.min.js'),
                asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
                asset('global/plugins/jquery-validation/js/additional-methods.min.js'),
            ],
            'styles' => [
                asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
                asset('global/plugins/select2/css/select2.min.css'),
                asset('global/plugins/select2/css/select2-bootstrap.min.css'),
            ],
            'title' => _t('member.memberManagement'),
            'heading_text' => _t('member.memberManagement'),
            'breadcrumbs' => [
                _t('index.home') => 'admin.home',
                _t('member.memberManagement') => 'management.members'
            ],
            'packages' => Package::get(),
            'ranks' => AdvancedRank::get(),
        ];

        $data['investment_client'] = InvestmentClient::where('email', $data['user']->email)->latest()->first();

        return view('Admin.Management.Member.index', $data);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    function requestUnit(Request $request)
    {
        if (!$action = $request->input('unit'))
            return response()->json(['invalid request'], 403);

        defineAction('memberManagement', 'unitAction', $action);

        return defineFilter('memberManagement', method_exists($this, $action) ? app()->call([$this, $action], (array)$request->input('params')) : '', 'unitFilter', $action);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    function memberSlice(Request $request)
    {
        if (!$request->input('user'))
            return response()->json(['invalid request !']);

        $data['user'] = User::find($request->input('user'));

        return view('Admin.Management.Member.Partials.slice', $data);
    }

    /**
     * @param Request $request
     * @param ModuleServices $moduleServices
     * @param TransactionServices $transactionServices
     * @return Factory|View
     */
    function wallets(Request $request, ModuleServices $moduleServices, TransactionServices $transactionServices)
    {
        $data['user'] = $user = User::find($request->input('user', loggedId()));
        $data['wallets'] = array_map(function ($instance) use ($transactionServices, $user) {
            /** @var WalletModuleInterface|ModuleBasicAbstract $instance */
            $instance->data = collect([
                'balance' => $instance->getBalance($user),
                'name' => $instance->getRegistry()['name'],
                'cashIn' => [
                    'amount' => $instance->credited($user)->sum('amount'),
                    'txnNos' => $instance->credited($user)->count(),
                    'graph' => $this->graphData($instance->credited($user)),
                ],
                'cashOut' => [
                    'amount' => $instance->debited($user)->sum('amount'),
                    'txnNos' => $instance->debited($user)->count(),
                    'graph' => $this->graphData($instance->debited($user)),
                ],
            ]);
            return $instance;
        }, $moduleServices->getWalletPool('active'));
        //dd($data);
        return view('Admin.Management.Member.Partials.wallets', $data);
    }

    /**
     * @param Builder $query
     * @param string $groupBy
     * @return Collection|Builder|array
     */
    function graphData(Builder $query, $groupBy = 'month')
    {
        /** @var TransactionServices $transactionServices */
        $transactionServices = app(TransactionServices::class);
        $transactionServices->groupBy($query, $groupBy);

        return $query->orderBy('created_at')->get()->map(function ($value) {
            return [
                $value->created_at->format('M-Y'),
                $value->total
            ];
        })->toArray();
    }

    /**
     * @param Request $request
     * @param ModuleServices $moduleServices
     * @return Factory|View
     */
    function commissions(Request $request, ModuleServices $moduleServices)
    {
        $data['user'] = $user = User::find($request->input('user'));
        $data['commissions'] = array_map(function ($module) use ($user, $request) {
            return tap($module, function ($module) use ($user) {
                /** @var CommissionModuleInterface|ModuleBasicAbstract $module */
                $module->data = [
                    'credited' => [
                        'txn' => ($credited = $module->credited($user)) ? $credited->get() : new Collection(),
                        'amount' => $credited ? $credited->sum('amount') : 0,
                        'graph' => json_encode($credited ? $this->graphData($credited) : [])
                    ],
                    'pending' => [
                        'txn' => ($pending = $module->pending($user)) ? $pending->get() : new Collection(),
                        'amount' => $pending ? $pending->sum('amount') : 0,
                        'graph' => json_encode($pending ? $this->graphData($pending) : [])
                    ],
                ];
            });
        }, $moduleServices->getCommissionPool('active'));

        return view('Admin.Management.Member.Partials.commissions', $data);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    function profile(Request $request)
    {
        $highestRank = AdvancedRankAchievementHistory::where('user_id', $request->input('user'))->max('rank_id');
        $data = [
            'user' => $user = User::with('repoData.parentUser.repoData','package', 'repoData.sponsorUser.repoData')
                ->find($request->input('user')),
            'scripts' => [
                asset('global/plugins/select2/js/select2.full.min.js'),
                asset('global/plugins/jquery-validation/js/jquery.validate.min.js'),
                asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'),
            ],
            'styles' => [
                asset('global/plugins/select2/css/select2.min.css'),
                asset('global/plugins/select2/css/select2-bootstrap.min.css'),
                asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'),
            ],
            'countries' => getCountries()->toArray(),
            'states' => getStates($user->metaData->country_id),
            'cities' => getCities($user->metaData->state_id),
            'highestRank' => $highestRank ? AdvancedRank::find($highestRank)->name : 'NA',
        ];
        
        $wiseTransaction = TransferWiseTransaction::where('reference', $user->customer_id)->where('context', 'Registration')->first();
        $b2bTransaction = B2BPayTransaction::where('reference_id', $user->customer_id)->where('context', 'Registration')->first();
        $data['register_pay_status'] = false;
        if (!isset($wiseTransaction->id) && !isset($b2bTransaction))
            $data['register_pay_status'] = true;
        $expiry_date = $user->expiry_date;
        $today = date('Y-m-d');
        $data['subscribe_pay_status'] = false;
        if (isset($expiry_date) && $expiry_date != '0000-00-00' && strtotime($expiry_date) > strtotime($today))
            $data['subscribe_pay_status'] = true;



        $data['investment_client'] = InvestmentClient::where('email', $data['user']->email)->latest()->first();

        return view('Admin.Management.Member.Partials.profile', $data);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    function userCard(Request $request)
    {
        return view('Admin.Management.Member.Components.memberCard', ['slot' => $request->input('user')]);
    }

    /**
     * @param Request $request
     * @param UtilityServices $utilityServices
     * @param UserServices $userServices
     * @return bool
     */
    function profileProfile(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'country_id' => 'required',
            'city' => 'required',
            'passport_no' => 'required|alpha_num',
            'nationality' => 'required',
            'place_of_birth' => 'required',
            'date_of_passport_issuance' => 'required',
            'country_of_passport_issuance' => 'required',
            'passport_expirition_date' => 'required',
            'street_name' => 'required',
            'house_no' => 'required',
            'postcode' => 'required',
            'address_country' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $user = $userServices->getUser($request->input('userId'), 'metaData');

        $userInstance = User::find($request->input('userId'));
        $metaInstance = $userInstance->metaData();

        $userInstance->update(collect($request->all())->only(['phone', 'email'])->all());
        $metaInstance->update(collect($request->all())->only(['firstname', 'lastname', 'dob', 'gender',
            'country_id', 'city', 'nationality', 'passport_no', 'place_of_birth', 'date_of_passport_issuance',
            'country_of_passport_issuance', 'passport_expirition_date', 'street_name', 'house_no',
            'postcode', 'address_country', 'company_name', 'company_registration_nr', 'company_address', 'company_ubo_director'])->all());

        app()->call([$utilityServices, 'setActivityHistory'], ['operation' => 'member_profile_updated', 'data' =>
            [
                'user_id' => $request->input('userId'),
                'prev_basic_info' => collect($user)->only(['username', 'email', 'phone', 'password'])->all(),
                'updated_basic_info' => collect($request->all())->only(['phone', 'email']),
                'prev_meta_info' => $user->metaData,
                'updated_meta_info' => collect($request->all())->only(['firstname', 'lastname', 'dob', 'gender',
                    'country_id', 'city', 'passport_no', 'nationality', 'place_of_birth', 'date_of_passport_issuance',
                    'country_of_passport_issuance', 'passport_expirition_date', 'street_name', 'house_no',
                    'postcode', 'address_country', 'company_name', 'company_registration_nr', 'company_address', 'company_ubo_director'])
            ],
            'on_user_id' =>$request->input('userId')
        ]);

        return response()->json(['status' => true]);
    }

    function profileHidename(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $validator = Validator::make($request->all(), [
            'hide_name' => 'required'
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $user = $userServices->getUser($request->input('userId'), 'metaData');

        $userInstance = User::find($request->input('userId'));
        $metaInstance = $userInstance->metaData();

        $userInstance->update(collect($request->all())->only(['hide_name'])->all());

        return response()->json(['status' => true]);
    }

    function ipayoutAccountUpdate(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'address_country' => 'required',
            'postcode' => 'required',
            'email' => 'required',
            'dob' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $country_info = Country::find($request->input('address_country'));

        $IPayout_id = \App\Eloquents\Module::where('slug', 'Payment-IPayWallet')->first()->id;
        if (isset($IPayout_id)) {
            $moduleData = \App\Eloquents\ModuleData::where('module_id', $IPayout_id)->first()->module_data;
            $apiurl = $moduleData['url'];
            $MerchantGUID = $moduleData['merchantId'];
            $MerchantPassword = $moduleData['merchantPassword'];
            $currency = $moduleData['currency'];

            $params = array(
                'fn'                => 'eWallet_UpdateUser',
                'MerchantGUID'      => $MerchantGUID,
                'MerchantPassword'  => $MerchantPassword,
                'UserName'          => User::find($request->input('userId'))->username,
                'FirstName'         => $request->input('firstname'),
                'LastName'          => $request->input('lastname'),
                'Address1'          => $request->input('address'),
                'City'          => $request->input('city'),
                'State'             => $country_info->name,
                'ZipCode'           => $request->input('postcode'),
                'Country2xFormat'   => $country_info->code,
                'EmailAddress'      => $request->input('email'),
                'DateOfBirth'       => $request->input('dob'),
                'DefaultCurrency'   => $currency,
            );
            $params = json_encode($params);
            $ch = curl_init($apiurl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $ips_response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($ips_response);
            if ($response->response->m_Code == -2) {
                return response()->json(['status' => false, 'message' => $response->response->m_Text]);
            } else {
                return response()->json(['status' => true]);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Not Activated I-Payout module']);
        }
    }

    function ipayoutUsernameUpdate(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $validator = Validator::make($request->all(), [
            'old_username' => 'required',
            'new_username' => 'required'
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);


        $IPayout_id = \App\Eloquents\Module::where('slug', 'Payment-IPayWallet')->first()->id;
        if (isset($IPayout_id)) {
            $moduleData = \App\Eloquents\ModuleData::where('module_id', $IPayout_id)->first()->module_data;
            $apiurl = $moduleData['url'];
            $MerchantGUID = $moduleData['merchantId'];
            $MerchantPassword = $moduleData['merchantPassword'];
            $params = array(
                'fn'                => 'eWallet_UpdateUserName',
                'MerchantGUID'      => $MerchantGUID,
                'MerchantPassword'  => $MerchantPassword,
                'CurrentUserName'   => $request->input('old_username'),
                'NewUserName'       => $request->input('new_username'),
                'Comments'          => ''
            );
            $params = json_encode($params);
            $ch = curl_init($apiurl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $ips_response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($ips_response);
            if ($response->response->m_Code == -1) {
                return response()->json(['status' => false, 'message' => $response->response->m_Text]);
            } else {
                return response()->json(['status' => true]);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Not Activated I-Payout module']);
        }
    }

    function downgradePackage(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $user = User::where('username', $request->input('username'))->get()->first();
        if(!$user) 
            return response()->json(['status' => false, 'message' => 'please enter valid username']);

        DB::transaction(function () use ($user) {
            $user->update([
                'temp_package' => $user->package_id,
                'package_id' => 1,
                'expiry_date' => NULL
            ]);

            AdvancedRankUser::where('user_id', $user->id)->delete();

            getModule('Rank-AdvancedRank')->calculateUpLineRank($user);

            return response()->json(['status' => true]);
        });
    }

    function reInstatePackage(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $user = User::find($request->input('userId'));
        if(!$user) 
            return response()->json(['status' => false, 'message' => 'please enter valid user id']);

        $user->update([
            'package_id' => $user->temp_package ?? 2,
            'expiry_date'=>date('Y-m-d'),
            'updated_at'=>date('Y-m-d')
        ]);

        return response()->json(['status' => true]);
    }

    function unSubscribeEmail(Request $request, UtilityServices $utilityServices, UserServices $userServices)
    {
        if (isDemoVersion())
            return response("You can't manage modules in demo mode", 403);

        $user = User::find($request->input('userId'));
        if(!$user) 
            return response()->json(['status' => false, 'message' => 'please enter valid user id']);
        $email_address = $request->input('email');
        if (!isset($email_address))
            return response()->json(['status' => false, 'message' => 'please enter email']);

        $networkUser = User::where('email', $email_address)->first();
        $isUser = false;
        $isAlready = false;
        if (isset($networkUser)) {
            if ($networkUser->subscribe_status == $request->input('subscribe_status'))
                $isAlready = true;
            $networkUser->update([
                'subscribe_status' => $request->input('subscribe_status') ?? 1,
                'updated_at'=>date('Y-m-d')
            ]);
            $isUser = true;
        }
        $insiderUser = InsiderUser::where('email', $email_address)->first();
        if (isset($insiderUser)) {
            if ($insiderUser->subscribe_status == $request->input('subscribe_status'))
                $isAlready = true;
            $insiderUser->subscribe_status = $request->input('subscribe_status') ?? 1;
            $insiderUser->updated_at = date('Y-m-d');
            $insiderUser->save();
            $isUser = true;
        }
        if (!$isUser) {
            return response()->json(['status' => false, 'message' => "Current Email doesn't exist. Please enter Email correctly." ]);
        } else {
            if ($isAlready)
                if ($request->input('subscribe_status') == 1)
                    return response()->json(['status' => false, 'already' => true, 'message' => "You have already updated to Subscribe." ]);
                else
                    return response()->json(['status' => false, 'already' => true, 'message' => "You have already updated to Unsubscribe." ]);
            else
                return response()->json(['status' => true]);
        }
    }

    /**
     * @param Request $request
     * @param UtilityServices $utilityServices
     * @return bool
     */
    function profilePassword(Request $request, UtilityServices $utilityServices)
    {
        $validator = $validator = Validator::make($request->all(), $this->validationPasswordRules());
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        if (!$user = User::find($request->input('userId')))
            return response()->json(['user' => false, 'message' => 'Invalid user']);

        if ($request->input('password') && Hash::check($request->input('password'), User::find($request->input('userId'))->password)) {
            return response()->json(['status' => false, 'message' => 'Please enter new password different than existing password']);
        }
        // update password
        $user->update(['password' => bcrypt($request->input('password'))]);
        // set in activity history
        app()->call([$utilityServices, 'setActivityHistory'], ['operation' => 'member_password_changed', 'data' => ['user' => $request->input('userId')],
            'on_user_id' =>$request->input('userId'),
            ]);
        // Notify user for password change
        /*   systemNotify([
               'operation' => 'Password change',
           ], loggedUser());*/

        return response()->json(['status' => true]);
    }

    /**
     * @return array
     */
    public function validationPasswordRules()
    {
        return [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
    }

    /**
     * @param Request $request
     * @return void
     */
    function profileSocial(Request $request)
    {
        $metaInfo = collect($request->all());

        $userInstance = User::find($request->input('userId'));
        $metaInstance = $userInstance->metaData();
        $metaInstance->update($metaInfo->only(['facebook', 'twitter', 'google_plus', 'linked_in', 'about_me'])->all());
    }

    /**
     * @param Request $request
     * @return void
     */
    function profilePayout(Request $request)
    {
        $metaInfo = collect($request->all());

        $userInstance = User::find($request->input('userId'));
        $metaInstance = $userInstance->metaData();
        $metaInstance->update($metaInfo->only(['bank_name', 'acc_number'])->all());
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    function personalized(Request $request)
    {
        $data = [
            'user' => User::with('repoData.parentUser.repoData', 'repoData.sponsorUser.repoData')
                ->find($request->input('user')),
        ];

        return view('Admin.Management.Member.Partials.personalized', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    function Transactions(Request $request)
    {

        $moduleId = $request->input('moduleId');

        return getModule((int)$moduleId)->getMemberTransactionList($request);
    }

    /**
     * @param Request $request
     * @return User[]|Builder[]|Collection
     */
    function filter(Request $request)
    {
        $filters = collect($request->all());
        $data = User::with('repoData', 'metaData', 'rank')
            ->when($rank = $filters->get('rank'), function ($query) use ($rank) {
                /** @var Builder $query */
                $query->whereHas('rank', function ($query) use ($rank) {
                    /** @var Builder $query */
                    $query->where('rank_id', $rank);
                });
            })
            ->when($keyword = $filters->get('keyword'), function ($query) use ($keyword) {
                /** @var Builder $query */
                $query->where(function ($query) use ($keyword) {
                    /** @var Builder $query */
                    $query->whereRaw('concat(username," ",email," ",phone," ", customer_id) LIKE ?', "%{$keyword}%")
                        ->orWhere(function ($query) use ($keyword) {
                            /** @var Builder $query */
                            $query->whereHas('metaData', function ($query) use ($keyword) {
                                /** @var Builder $query */
                                $query->whereRaw('concat(firstname," ",lastname," ",dob," ",pin) LIKE ?', "%{$keyword}%");
                            });
                        });
                });
            })
            ->when($pack = $filters->get('package'), function ($query) use ($pack) {
                /** @var Builder $query */
                $query->where('package_id', $pack);

            })
            ->when($memberId = $filters->get('memberId'), function ($query) use ($memberId) {
                /** @var Builder $query */
                $query->whereHas('metaData', function ($query) use ($memberId) {
                    /** @var Builder $query */
                    $query->where('customer_id', $memberId);
                });
            })
            ->get();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @param UtilityServices $utilityServices
     * @return JsonResponse
     */
    function profileUsername(Request $request, UtilityServices $utilityServices)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:5|unique:users',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        $userInstance = User::find($request->input('userId'));
        $oldName = $userInstance->username;
        $userInstance->update(collect($request->all())->only(['username'])->all());
        app()->call([$utilityServices, 'setActivityHistory'], ['operation' => 'member_username_changed', 'data' => [
                'user' => $request->input('userId'),
                'updated_basic_info' => collect($request->all())->only(['username']),
                'prev_basic_info'   => collect(['username'=>$oldName]),
             ],
            'on_user_id'=>$request->input('userId')
            ]);

        return response()->json(['status' => true]);
    }
}
