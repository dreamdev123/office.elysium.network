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

namespace App\Blueprint\Services;

use App\Blueprint\Interfaces\Module\ModuleBasicAbstract;
use App\Blueprint\Interfaces\Module\WalletModuleInterface;
use App\Blueprint\Support\Transaction\RegistrationCallback;
use App\Blueprint\Support\Transaction\ExpirationCallback;
use App\Blueprint\Traits\UserDataFilter;
use App\Components\Modules\General\HoldingTank\ModuleCore\Eloquents\HoldingTank;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankAchievementHistory;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
use App\Components\Modules\Rank\AdvancedRank\ModuleCore\Eloquents\AdvancedRankUser;
use App\Components\Modules\Report\ClientReport\ModuleCore\Eloquents\CapitalUser;
use App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents\InsiderUser;
use App\Eloquents\CustomField;
use App\Eloquents\Package;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionOperation;
use App\Eloquents\User;
use App\Eloquents\UserRepo;
use App\Http\Requests\RegistrationValidation;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Blueprint\Services\ExternalMailServices;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use GeoIP;
use Hash;
/**
 * Class UserServices
 * @package App\Blueprint\Services
 */
class UserServices
{
    use UserDataFilter;

    /**
     * @param array $options
     * @return User|Builder|\Illuminate\Database\Eloquent\Collection
     */
    function topEarners($options = [])
    {
        /** @var TransactionServices $transactionService */
        $transactionService = app(TransactionServices::class);
        $options = collect([
            'sortBy' => 'earnings'
        ])->merge($options);
        $query = User::query()->toBase();
        $payeeColumn = $query->getGrammar()->wrap((new Transaction)->getTable() . '.' . 'payee');
        $userColumn = $query->getGrammar()->wrap((new User)->getTable() . '.' . (new User)->getKeyName());
        isAdmin() ? $model = User::query() : $model = User::find(loggedId())->descendants([], 'sponsor', false);
        $usersQuery = $model->selectSub($transactionService->getTransactions(collect([
            'operation' => TransactionOperation::slugToId('commission')
        ]))->whereRaw("$payeeColumn = $userColumn")->selectRaw('SUM(`amount`)')->getQuery(), 'earnings');

        return $this->getUsers($options, $usersQuery);
    }

    /**
     * @param Collection $args
     * @param null|User $userModel
     * @param bool $returnQuery
     * @param array $eagerLoads
     * @return Collection|Builder|mixed
     */
    function getUsers(Collection $args, $userModel = null, $returnQuery = false, $eagerLoads = [])
    {
        $defaults = collect([
            'sortBy' => 'created_at',
            'orderBy' => 'DESC',
            'fromDate' => User::min('created_at'),
            'toDate' => Carbon::now()->toDateTimeString(),
            'insiderToDate' => Carbon::now()->format('Y-m-d'),
            'status' => true,
        ]);
        $options = $defaults->merge($args);
        $eagerLoads = $eagerLoads ?: ['repoData', 'metaData'];
        /** @var User|Builder $userModel */
        $userModel = $userModel ?: (new User)->newQuery();
        $columns = Schema::getColumnListing((new User)->getTable());
        /** @var Builder $query */
        $query = $userModel->with($eagerLoads)->addSelect($columns)
            ->when(($userId = $options->get('userId')), function ($query) use ($userId) {
                /** @var Builder $query */
                $query->where('id', $userId);
            })
            ->when(($status = $options->get('status')), function ($query) use ($status) {
                /** @var Builder $query */
                //$query->where('status', $status);
            })->when($memberId = $options->get('memberId'), function ($query) use ($memberId) {
                /** @var Builder $query */
                $query->where('customer_id', $memberId);
            })->when($options->get('insider'), function ($query) use ($options) {
                /** @var Builder $query */
                $query->where('insider_expiry_date', '>', $options->get('insiderToDate'));
            })->when($options->get('groupBy'), function ($query) use ($options) {
                /** @var Builder $query */
                $this->groupBy($query, $options->get('groupBy'));
            })
            ->when($options->get('limit'), function ($query) use ($options) {
                /** @var Builder $query */
                $query->take($options->get('limit'));
            })
            ->whereBetween('created_at', [$options->get('fromDate'), $options->get('toDate')])
            ->orderBy($options->get('sortBy'), $options->get('orderBy'));

        if ($returnQuery) return $query;

        try {
            $query->get();
        } catch (Exception $e) {
            dd($query->toSql());
        }
        return $query->get()->map(function ($user) use ($options) {
            return $this->withExtraInfo($user, ['wallet' => $options->get('wallet')]);
        })->filter(function ($user) use ($options) {
            if ($walletId = $options->get('wallet')) {
                /** @var WalletModuleInterface|ModuleBasicAbstract $wallet */
                $wallet = getModule((int)$walletId);
                //$slug = Str::camel($wallet['registry']['key']);
                $slug = Str::camel($wallet->registry['key']);
                $walletBalance = isset($user->wallet[$slug]['balance']) ? $user->wallet[$slug]['balance'] : $wallet->getBalance($user);
                // Check user balance
                if (($balance = $options->get('balance')) && $walletBalance !== $balance)
                    return false;
                // Check minimum balance
                if (($minBalance = $options->get('minBalance')) && $walletBalance < $minBalance)
                    return false;
                // Check maximum balance
                if (($maxBalance = $options->get('maxBalance')) && $walletBalance > $maxBalance)
                    return false;
            }

            if ($userId = $options->get('userId')) if ($user->id !== (int)$userId) return false;

            return true;
        });
    }

    /**
     * @param Builder $query
     * @param string $groupBy
     * @param string $timestampColumn
     * @return Builder
     */
    function groupBy(Builder &$query, $groupBy = 'month', $timestampColumn = 'created_at')
    {
        switch ($groupBy) {
            case 'month':
            case 'year':
            case 'day':
            case 'hour':
                /** @var Builder $query */
                $query->groupBy(DB::raw("$groupBy($timestampColumn)"))
                    ->selectRaw("MONTH($timestampColumn) month, YEAR($timestampColumn) year, DAY($timestampColumn) day, HOUR($timestampColumn) hour, COUNT(1) total");
                break;
            default:
                /** @var Builder $query */
                $query->groupBy($groupBy);
                break;
        }

        return $query;
    }

    function expire_alarm()
    {
        var_dump(date('Y-m-d',strtotime(date('Y-m-d') . ' + 7days')));
        $users = User::where('updated_at',"<",date('Y-m-d'))->whereDate('expiry_date','=',date('Y-m-d',strtotime(date('Y-m-d') . ' + 7days')))->get();

        var_dump(count($users));
        foreach ($users as $key => $user) {
            $user->update(['updated_at'=>date('Y-m-d H:i:s')]);
            $email = new ExternalMailServices;
            $email->send_expire_alarm($user);
        }

        $today = date('Y-m-d');
        $users = User::where('expiry_date',">",date('Y-m-d'))->get();
        foreach ($users as $user) {
            $expiry_date = $user->expiry_date;
            $numExpiryDates = range(strtotime($today), strtotime($expiry_date),86400);
            $numExpiryDates = count($numExpiryDates);
            $num = $numExpiryDates / 30;
            $safechargesubscription = SafechargeSubscription::where('user_id',$user['id'])->first();
            if ((int)$num > 0 && $user->package->slug != 'affiliate' && $user->package->slug != 'influencer' && $user->package->slug != 'client' && !$safechargesubscription) {
                $datetime = new \DateTime($expiry_date);
                if($datetime->modify('-'.(int)$num.' month')->format('Y-m-d') == date('Y-m-d'))
                {
                    $data['user'] = $user;
                    defineAction('expireuser', 'expire', collect(['result' => $data]));
                }
            }
        }
        
        // $users = User::whereDate('updated_at',"<",date('Y-m-d'))->whereDate('expiry_date','=',date('Y-m-d'))->get();
        // foreach ($users as $key => $user) {
        //     $user->update(['updated_at'=>date('Y-m-d H:i:s')]);
        //     $email = new ExternalMailServices;
        //     $email->send_expire_alarm($user);
        // }


    }

    /**
     * @param User $user
     * @param array $args
     * @return User
     */
    function withExtraInfo(User $user, $args = [])
    {
        $args = collect($args ?: []);
        $moduleService = app(ModuleServices::class);
        // Setting wallet details
        foreach ($moduleService->getWalletPool() as $key => $wallet) {
            /** @var WalletModuleInterface|ModuleBasicAbstract $wallet */
            $slug = $wallet->getRegistry()['key'];

            if ($slug == 'BusinessWallet') continue;

            if (($walletId = (int)$args->get('wallet')) && ($wallet->moduleId !== $walletId)) continue;
            /** @var WalletModuleInterface|ModuleBasicAbstract $wallet */
            $wallet = callModule($wallet->moduleId);
            $user->wallet = array_merge((array)$user->wallet, [
                Str::camel($slug) => [
                    'balance' => $wallet ? $wallet->getBalance($user) : 0,
                ]
            ]);
        }

        return $user;
    }

    /**
     * @param Collection $args
     * @param null $repoModel
     * @return Collection|Builder|mixed
     */
    function getUserRepo(Collection $args, $repoModel = null)
    {
        $defaults = collect([
            'sortBy' => 'created_at',
            'orderBy' => 'DESC',
            'fromDate' => (new User)->min('created_at'),
            'toDate' => Carbon::now()->toDateTimeString(),
            'status' => true,
        ]);
        $options = $defaults->merge($args);
        /** @var User|Builder $userModel */
        $repoModel = $repoModel ?: (new UserRepo())->newQuery();
        $repoTable = $repoModel->getModel()->getTable();
        $userTable = (new User)->getTable();
        $userKey = (new User)->getKeyName();
        $userForeignKey = (new User)->getForeignKey();

        /** @var \Illuminate\Database\Eloquent\Collection $users */
        return $repoModel->with('user.metaData')
            ->selectRaw("$repoTable.*, $userTable.*")
            ->from($repoTable)
            ->join($userTable, "$userTable.$userKey", '=', "$repoTable.$userForeignKey")
            ->whereBetween("$userTable.created_at", [$options->get('fromDate'), $options->get('toDate')])
            ->when($options->get('groupBy'), function ($query) use ($userTable, $options) {
                $this->groupBy($query, $options->get('groupBy'), $userTable . '.' . $this->wrapColumn('created_at'));
            });
    }

    /**
     * @param $value
     * @return string
     */
    function wrapColumn($value)
    {
        $query = User::query()->toBase();

        return $query->getGrammar()->wrap($value);
    }

    /**
     * @param array $options
     * @return User|Builder|\Illuminate\Database\Eloquent\Collection
     */
    function topSponsors($options = [])
    {
        $options = collect([
            'sortBy' => 'downlines'
        ])->merge($options);
        $query = UserRepo::query()->toBase();
        $userColumn = $query->getGrammar()->wrap((new User)->getTable() . '.' . (new User)->getKeyName());
        $matchingColumn = $query->getGrammar()->wrap((new UserRepo())->getTable() . '.' . 'sponsor_id');
        isAdmin() ? $model = User::query() : $model = User::find(loggedId())->descendants([], 'sponsor', false);
        $usersQuery = $model->selectSub($query->whereRaw("$userColumn = $matchingColumn")
            ->selectRaw('COUNT(1)'), 'downlines');

        return $this->getUsers($options, $usersQuery);
    }

    /**
     * @param User|integer $user
     * @param array|string $relations
     * @return User
     */
    function getUser($user, $relations = null)
    {
        $user = $user instanceof User ? $user : User::find($user);

        if ($relations) $user->load(is_array($relations) ? implode(',', $relations) : $relations);

        return $this->withExtraInfo($user);
    }

    /**
     * Handles user data insertion tasks
     *
     * @param Collection $data
     * @param bool $addToRepo
     * @return User|boolean
     */
    function addUser(Collection $data, $addToRepo = true)
    {
        /** @var ConfigServices $config */
        // check for dynamic username generation
        if (getConfig('registration', 'username_type') != 'static')
            $data->put('username', $this->randomUsername());
        //Adding member id
        $data->put('customer_id', $this->generateCustomerId());
        $data->put('sponsor_id', $sponsorId = idFromUsername($data->get('sponsor')));
        $package = Package::find($data->get('selectedPackage'));

        if($package)
        {
            $data->put('package_id', $package->id);
            $data->put('signup_package', $package->id);
            if ($package->validity_in_month > 0)
                $data->put('expiry_date', Carbon::now()->addMonth($package->validity_in_month));
            
            if ($package->insider_member_in_month > 0)
                $data->put('insider_expiry_date', Carbon::now()->addMonth($package->insider_member_in_month));    
        }
        
        // Adding user basic data
        $user = User::create($this->formatBasicData($data));
        // Adding user repo data
        /** @var User $sponsor */
        $sponsor = User::find($sponsorId);
        $moduleServices = app(ModuleServices::class);
        $holdingTank = getModule('General-HoldingTank');
        if ($holdingTank && $moduleServices->isActive($holdingTank->getRegistry()['slug']) && $holdingTank->getModuleData(true)->get('holding_tank') && $user->userSponsor->holding_tank_active) {
            $registerAutomatically = HoldingTank::where('user_id', $sponsor->id)->get()->first();
            if (isset($registerAutomatically->status) && $registerAutomatically->status && $sponsor->repoData)
                $addToRepo = true;
            else
                $addToRepo = false;
        }

        if ($addToRepo) {
            $repoData = $this->formatRepoData($data, $user);
            $prepend = defineFilter('appendOrPrepend', [], 'placement', $repoData);
            $user->repoData($repoData, $prepend);
            $user->update(['is_confirmed' => true]);
        } elseif (isset($registerAutomatically->status) && $registerAutomatically->status) {
            $user->update([
                'is_confirmed' => true,
                'preferred_position' => $registerAutomatically->default_position
            ]);
        }

       
        // Adding to meta
        $user->metaData($this->formatMetaData($data));
        // Adding user role data
        $user->userRoleData($data->get('role', ['type_id' => 3, 'sub_type_id' => 3]));

        if ($user->package_id > 1) {
            // Adding Insider user
            $attr_user = [
              'customer_id' => $user->customer_id,
              'sponsor_id' => $user->id,
              'username' => $user->username,
              'first_name' => $user->metaData->firstname,
              'last_name' => $user->metaData->lastname,
              'email' => $user->email,
              'password' => Hash::make($data->get('password')),
              'status' => 'enabled',
              'gender' => $user->metaData->gender,
              'mobile_number' => $user->phone,
              'street_name' => $user->metaData->street_name,
              'house_number' => $user->metaData->house_no,
              'country' => $user->metaData->country_id,
              'city' => $user->metaData->city,
              'postal_code' => $user->metaData->postcode,
              'passport_id' => $user->metaData->passport_no,
              'date_of_birth' => $user->metaData->dob,
              'date_of_passport_issuance' => $user->metaData->date_of_passport_issuance,
              'date_of_passport_expiration' => $user->metaData->passport_expirition_date,
              'country_of_birth' => $user->metaData->place_of_birth,
              'country_of_passport_issuance' => $user->metaData->country_of_passport_issuance,
              'nationality' => $user->metaData->nationality,
              'ip' => GeoIP::getLocation()->ip,
              'expiry_date' => $user->expiry_date,
              'state' => $user->package_id > 9 ? 'annual' : 'monthly',
            ];
            $insiderUser = new InsiderUser($attr_user);
            $insiderUser->save();
        }
            
        
        return $user;
    }

    function increaseexpire(User $user, Transaction $transaction)
    {
        $amount = $transaction->amount;
        if($user->package->slug == 'affiliate' || $user->package->slug == 'influencer' || $user->package->slug == 'client')
        {
            $user->update(['expiry_date'=>null]);
        }
        else
        {
            $month = 0;
            if ($amount == 79.95 || $amount == 97) {
                $month = 1;
            } elseif ($amount == 240) {
                $month = 3;
            } elseif ($amount == 480 || $amount == 456) {
                $month = 6;
            } elseif ($amount == 960 || $amount == 864 || $amount == 880) {
                $month = 12;
            }
            $datetime = new \DateTime($user->expiry_date);
            $currenttime = new \DateTime('now');
            if($datetime->getTimestamp() > $currenttime->getTimestamp())
            {
                $user->update([
                    'expiry_date'=>$datetime->modify('+'.$month.' month')->format('Y-m-d')
                ]);
            } else {
                $user->update([
                    'expiry_date'=>Carbon::now()->addMonth($month)->format('Y-m-d')
                ]);
            }
        }
        $month = 0;
        if ($amount == 240 || $amount == 199) {
            $month = 3;
        } elseif ($amount == 480 || $amount == 456 || $amount == 299) {
            $month = 6;
        } elseif ($amount == 960 || $amount == 864 || $amount == 880 || $amount == 499) {
            $month = 12;
        }

        $datetime = new \DateTime($user->insider_expiry_date);
        $currenttime = new \DateTime('now');
        if($datetime->getTimestamp() > $currenttime->getTimestamp())
        {
            $user->update([
                'insider_expiry_date'=>$datetime->modify('+'.$month.' month')->format('Y-m-d')
            ]);
        } else {
            $user->update([
                'insider_expiry_date'=>Carbon::now()->addMonth($month)->format('Y-m-d')
            ]);
        }
        return $user;
    }


    function addUserDirectly(Collection $data){
        if(isset($data->expire) && $data->expire)
        {
            $user = loggedUser();
            $package = Package::find($user->package_id);
            $user->update([
                'expiry_date'=>Carbon::now()->addMonth($package->validity_in_month)
            ]);
            return $user;
        }
        /** @var ConfigServices $config */
        $config = app(ConfigServices::class);
        // check for dynamic username generation
        if (getConfig('registration', 'username_type') != 'static')
            $data->put('username', $this->randomUsername());
        //Adding member id
        $data->put('customer_id', $this->generateCustomerId());
        $data->put('sponsor_id', $sponsorId = idFromUsername($data->get('sponsor')));
        //$package = getPackageInfo($data->get('products')[0]['productId']);
        $package = Package::find($data->get('selectedPackage'));
        $data->put('package_id', $package->id);
        $data->put('signup_package', $package->id);

        if ($package->validity_in_month > 0)
            $data->put('expiry_date', Carbon::now()->addMonth($package->validity_in_month));
        if ($package->insider_member_in_month > 0)
            $data->put('insider_expiry_date', Carbon::now()->addMonth($package->insider_member_in_month));  
        // Adding user basic data
        $user = User::create($this->formatBasicData($data));

        $Parent = UserRepo::where('user_id','=',$data['parent'])->first();//13

        $data->put('placement',$data->get('parent'));
        $data->put('user_level',$Parent->user_level+1);

        $repoData = $this->formatRepoData($data, $user);
        $prepend = defineFilter('appendOrPrepend', [], 'placement', $repoData);
        $user->repoData($repoData, $prepend);
        $user->update(['is_confirmed' => true]);

        UserRepo::where([
            'parent_id'=>$data->get('parent_id'),
            'position'=>$data->get('position')
        ])->where('user_id','<>',$user->id)
          ->update([
            'parent_id'=>$user->id,
            'user_level'=>$Parent->user_level+2
        ]);

        // Extracts custom fields from data
        $customFields = array_map(function ($value) {
            return new CustomField(array_only($value, ['meta_key', 'meta_value', 'group']));
        }, $config->extractCustomFields($data->all()));
        // Adding to meta
        $user->metaData($this->formatMetaData($data));

        // Adding user role data
        $user->userRoleData($data->get('role', ['type_id' => 3, 'sub_type_id' => 3]));
        // adding custom fields in case registering from client-side form
        $user->customFields($customFields);

        return $user;
    }
    /**
     * Generate random username that is available to register
     *
     * @return bool|string
     */
    function randomUsername()
    {
        $prefix = getConfig('registration', 'username_prefix');
        $length = getConfig('registration', 'username_length');

        while (!User::where('username', $username = $prefix . randomString($length))->exists()) {
            return $username;
            break;
        }

        return false;
    }

    /**
     * Verify sponsor name
     *
     * @param $name
     * @return bool
     */
    function verifySponsor($name)
    {
        return User::where('username', $name)->exists();
    }

    /**
     * Verify username name
     *
     * @param array $param user details
     * @return boolean
     */
    function verifyUsername($param)
    {
        if (strlen($param) < 6) {
            return false;
        }

        return User::where('username', $param)->exists();
    }

    /**
     * Verify email
     *
     * @param string $param user details
     * @return boolean
     */
    function verifyEmail($param)
    {
        return !User::where('email', $param)->exists() && filter_var($param, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param PaymentServices $paymentServices
     * @param RegistrationValidation $request
     * @param RegistrationCallback $registrationCallback
     * @param OrderServices $orderServices
     * @param ModuleServices $moduleServices
     */
    function handleRegistrationRequest(
        PaymentServices $paymentServices,
        RegistrationValidation $request,
        RegistrationCallback $registrationCallback,
        OrderServices $orderServices, ModuleServices $moduleServices)
    {
        $orderServices->keepOrder(true, $request);
        $paymentServices->setGateway((int)$request->input('gateway'))
            ->setCallback($registrationCallback)
            ->setPayable($paymentServices->getPayable('cart')
                ->setToModule($moduleServices->slugToId('Wallet-BusinessWallet'))
                ->setOperation('registration')
                ->setPayee(User::companyUser())
                ->setPayer(loggedUser() ?: User::companyUser())
                ->setContext('Registration'));
        registerAction('postPaymentProcessAction', function ($response) {
            app()->call([$this, 'unsetRegistrationData']);
        }, 'registration');
    }

    function handleExpireRegistrationRequest(
        PaymentServices $paymentServices,
        Request $request,
        ExpirationCallback $expirecallback,
        OrderServices $orderServices, ModuleServices $moduleServices)
    {
        $orderServices->keepOrder(true, $request);
        $paymentServices->setGateway((int)$request->input('gateway'))
            ->setCallback($expirecallback)
            ->setPayable($paymentServices->getPayable('cart')
                ->setToModule($moduleServices->slugToId('Wallet-BusinessWallet'))
                ->setOperation('subscription')
                ->setPayee(User::companyUser())
                ->setPayer(loggedUser() ?: User::companyUser())
                ->setContext('Subscription'));
        registerAction('postPaymentProcessAction', function ($response) {
            app()->call([$this, 'unsetRegistrationData']);
        }, 'subscription');
    }

    /**
     * Unset session data after successful registration
     *
     * @param CartServices $cartServices
     * @param OrderServices $orderServices
     * @return bool
     */
    function unsetRegistrationData(CartServices $cartServices, OrderServices $orderServices)
    {
        $cartServices->clear();
        $orderServices->clearSession();

        return true;
    }

    /**
     * Get ProfileData
     *
     * @param $id
     * @return mixed
     */
    function getProfileData($id)
    {
        $user = DB::table('users')
            ->select('*')
            ->join('user_repo', 'users.id', '=', 'user_repo.user_id')
            ->join('user_meta', 'users.id', '=', 'user_meta.user_id')
            ->where('users.id', '=', $id)
            ->get()->first();

        $sponsor = User::find($user->sponsor_id);

        $user->sponsor_name = $sponsor ? $sponsor->username : '';

        $placement = User::find($user->parent_id);

        $user->placement_name = $placement ? $placement->username : '';

        return $user;
    }

    /**
     * @param $userID
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    function getSponsorId($userID)
    {
        return User::find($userID)->sponsor()->id;
    }

    /**
     * @param $userID
     * @return mixed
     */
    function getPlacementId($userID)
    {
        return User::find($userID)->parent()->id;
    }

    /**
     * @param $userID
     * @return mixed
     */
    function getUserType($userID)
    {
        return User::find($userID)->userType->title;
    }

    /**
     * @param $userID
     * @return mixed
     */
    function getSponsorMembersCount($userID)
    {
        return  User::where('sponsor_id', $userID)->count();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function getUserProfile($id)
    {
        return User::with(['RepoData', 'MetaData'])->find($id);
    }

    /**
     * @return string
     */
    function generateCustomerId()
    {
        // 6 digit random number, unique in DB
        $attempt = 1;
        $attempt_max = 5;
        $customer_id = null;
        do {
            $customer_id = rand(100000,999999);
            $attempt++;
        } while (User::where('customer_id', $customer_id)->exists() && CapitalUser::where('client_id', $customer_id)->exists() && InsiderUser::where('customer_id', $customer_id)->exists() && $attempt <= $attempt_max);

        if ($attempt > $attempt_max) {
            \Log::error("[\App\Blueprint\Services\UserServices::generateCustomerId] Could not generate unique customer_id");
            abort(500, 'Could not generate unique Customer ID. Please contact Support.');
        }

        return $customer_id;
    }

    function pendingCallbackResponse($transaction)
    {
        /** @var OrderServices $orderServices */
        $orderServices = app(OrderServices::class);

        /** @var CartServices $cartServices */
        $cartServices = app(CartServices::class);

        return [
            'transaction_id' => $transaction->id,
            'orderData' => $orderServices->getOrderData(),
            'cartDetails' => $cartDetails = $cartServices->cartTotal(),
        ];
    }
}
