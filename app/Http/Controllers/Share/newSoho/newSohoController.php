<?php

namespace App\Http\Controllers\Share\newSoho;

use App\Blueprint\Services\ExternalMailServices;
use App\Blueprint\Services\LocationServices;
use App\Blueprint\Services\TransactionServices;
use App\Blueprint\Services\UserServices;
use App\Blueprint\Services\UtilityServices;
use App\Blueprint\Traits\ProfileFields;
use App\Blueprint\Traits\UserDataFilter;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
use App\Components\Modules\General\SoHo\ModuleCore\Eloquents\TellFriendMails;
use App\Components\Modules\General\EmailBroadcasting\ModuleCore\Eloquents\InsiderUser;
use App\Components\Modules\General\HoldingTank\Controllers\HoldingTankController;
use App\Eloquents\Country;
use App\Eloquents\User;
use App\Eloquents\UserRepo;
use App\Eloquents\UserMeta;
use App\Eloquents\UserRole;
use App\Eloquents\Commission;
use App\Eloquents\Transaction;
use App\Eloquents\TransactionOperation;
use App\Eloquents\TransactionDetail;
use App\Eloquents\Package;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use App\Mail\ClientSendQuote;
use Response;
use PDF;
use File;
use Image;
use DateTime;
use Mail;
use GeoIP;

class newSohoController extends Controller
{

  public function shareImage(Request $request)
  {
    $watermark = $request->watermark;
    $watermark = explode(".", $watermark);
    $customer_id = $request->customerId;
    $filename = $request->filename;
    $fileUrl = $customer_id.'/'.$watermark[0].'/'.$filename;
    return view('shareImage')->with(['fileUrl'=>$fileUrl]);
  }

  public function unSubscriptionCheck(Request $request)
  {
    $tId = $request->tId;
    if (isset($tId)) {
      $start = date('Y-m-d');
      $end = date('Y-m-d', strtotime(date('Y-m-d') . ' + 5days'));
      $tellFriendMail = TellFriendMails::find($tId);
      if (isset($tellFriendMail)) {
        $tellFriendMail->subscribe_status = 0;
        $tellFriendMail->save();
      }
      return view('unSubscription')->with(['status'=>true]);
    } else {
      return view('unSubscription')->with(['status'=>false]);
    }
  }

  public function calcInsiderCommission($insiderUserId = null)
  {
    $insiderUser = InsiderUser::find($insiderUserId);
    $attr_user = [
      'customer_id' => $insiderUser->customer_id,
      'username' => $insiderUser->username,
      'email' => $insiderUser->email,
      'password' => $insiderUser->password,
      'phone' => $insiderUser->mobile_number,
      'sponsor_id' => $insiderUser->sponsor_id,
      'expiry_date' => $insiderUser->expiry_date,
      'package_id' => 16,
      'signup_package' => 16,
      // 'is_comfirmed' => true
    ];
    $user = new User($attr_user);
    $user->save();

    $attr_user_meta = [
      'user_id' => $user->id,
      'firstname' => $insiderUser->first_name,
      'lastname' => $insiderUser->last_name,
      'dob' => $insiderUser->date_of_birth,
      'country_id' => $insiderUser->country,
      'gender' => $insiderUser->gender,
      'passport_no' => $insiderUser->passport_id,
      'nationality' => $insiderUser->nationality,
      'place_of_birth' => $insiderUser->country_of_birth,
      'date_of_passport_issuance' => $insiderUser->date_of_passport_issuance,
      'country_of_passport_issuance' => $insiderUser->country_of_passport_issuance,
      'passport_expirition_date' => $insiderUser->date_of_passport_expiration,
      'street_name' => $insiderUser->street_name,
      'house_no' => $insiderUser->house_number,
      'postcode' => $insiderUser->postal_code,
      'city' => $insiderUser->city,
      'company_name' => $insiderUser->company_name,
      'company_registration_nr' => $insiderUser->company_registration_nr,
      'company_address' => $insiderUser->company_address,
      'company_ubo_director' => $insiderUser->company_ubo_director,
    ];
    $userMeta = new UserMeta($attr_user_meta);
    $userMeta->save();

    $attr_user_role = ['user_id' => $user->id, 'type_id' => 3, 'sub_type_id' => 3];
    $userRole = new UserRole($attr_user_role);
    $userRole->save();
    
    $position = $user->userSponsor->repoData->default_binary_position;
    
    app()->call([app(HoldingTankController::class), 'placeUser'], [
        'userId' => $user->id,
        'position' => $position
    ]);

    getModule('Commission-MultiTierInsiderCommissions')->process([
      'user' => $user,
      'scope' => 'registration',
      'package' => $insiderUser->state,
    ]);

    $data['user'] = $user;
    defineAction('expireuser', 'expire', collect(['result' => $data]));

    return response()->json(['status' => true], 200);
  }
}