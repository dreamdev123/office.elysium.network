<?php

namespace App\Http\Controllers\User\newXoom;

use App\Blueprint\Services\ExternalMailServices;
use App\Blueprint\Services\LocationServices;
use App\Blueprint\Services\TransactionServices;
use App\Blueprint\Services\UserServices;
use App\Blueprint\Services\UtilityServices;
use App\Blueprint\Traits\ProfileFields;
use App\Eloquents\User;
use App\Blueprint\Traits\UserDataFilter;
use App\Eloquents\UserMeta;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use App\Eloquents\Transaction;
use App\Eloquents\Package;
use App\Components\Modules\Payment\SafeCharge\ModuleCore\Eloquents\SafechargeSubscription;
use App\Eloquents\Country;
use Carbon\Carbon;
use App\Mail\ClientSendQuote;
use Response;
use PDF;
use File;
use Image;
use DateTime;
use Mail;

class newXoomController extends Controller
{
	public function index(Request $request)
  {
    $user = loggedUser();

		return view('User.newXoom.videoCall')->with(['user'=>$user]);
	}

  public function videoRoom(Request $request)
  {
    $user = loggedUser();

    return view('User.newXoom.videoRoom')->with(['user'=>$user]);
  }
}