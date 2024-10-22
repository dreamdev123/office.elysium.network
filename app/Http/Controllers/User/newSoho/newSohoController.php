<?php

namespace App\Http\Controllers\User\newSoho;

require_once __DIR__ . '/facebook/graph-sdk/src/Facebook/autoload.php';
require_once __DIR__ . '/facebook/graph-sdk/src/Facebook/Exceptions/FacebookResponseException.php';
require_once __DIR__ . '/facebook/graph-sdk/src/Facebook/Exceptions/FacebookSDKException.php';
require_once __DIR__ . '/facebook/graph-sdk/src/Facebook/Helpers/FacebookRedirectLoginHelper.php';

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
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class newSohoController extends Controller
{

	public function contentCreator(Request $request)
  {
    $user = loggedUser();

    if (!file_exists('uploads/images/'.$user->customer_id))
    {
      mkdir('uploads/images/'.$user->customer_id, 0777 , true);
    }

    if (!file_exists('uploads/images/'.$user->customer_id))
    {
      mkdir('uploads/images/'.$user->customer_id, 0777 , true);
    }

    if (!file_exists('uploads/images/'.$user->customer_id))
    {
      mkdir('uploads/images/'.$user->customer_id, 0777 , true);
    }

    if (!file_exists('uploads/library/network'))
    {
      mkdir('uploads/library/network', 0777 , true);
    }

    if (!file_exists('uploads/library/capital'))
    {
      mkdir('uploads/library/capital', 0777 , true);
    }

    if (!file_exists('uploads/library/insider'))
    {
      mkdir('uploads/library/insider', 0777 , true);
    }

    if (!file_exists('uploads/samples/network'))
    {
      mkdir('uploads/samples/network', 0777 , true);
    }

    if (!file_exists('uploads/samples/capital'))
    {
      mkdir('uploads/samples/capital', 0777 , true);
    }

    if (!file_exists('uploads/samples/insider'))
    {
      mkdir('uploads/samples/insider', 0777 , true);
    }

    if (!file_exists('uploads/videos/network'))
    {
      mkdir('uploads/videos/network', 0777 , true);
    }

    if (!file_exists('uploads/videos/capital'))
    {
      mkdir('uploads/videos/capital', 0777 , true);
    }

    if (!file_exists('uploads/videos/insider'))
    {
      mkdir('uploads/videos/insider', 0777 , true);
    }

    $brand = $request->brand;
    if (!isset($brand)) {
      $brand = 'network';
    }

    $fileNames = [];
    $path = public_path('uploads/samples/'.$brand);
    $files = \File::allFiles($path);

    foreach($files as $file) {
      array_push($fileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }
    
		return view('User.newSoho.contentCreator')->with(['user'=>$user, 'fileNames'=>$fileNames, 'brand'=>$brand, 'files'=>json_encode($fileNames)]);
	}

  public function makeSohoImage(Request $request)
  {
    $user = loggedUser();
    $today = new DateTime();
    $today = $today->getTimestamp();
    $backgroundImage = $request->backgroundImage;
    $backgroundImageArray = explode(".", $backgroundImage);
    $filename = $user->customer_id . '.' . $backgroundImageArray[1];
    $brand = $request->brand;
    $fontweight = $request->fontweight;
    $aligntype = $request->aligntype;
    $content = $request->content;
    $message = [];
    $contentArray = explode("</div>", $content);
    if (count($contentArray) > 1) {
      foreach ($contentArray as $value) {
        if ($value) {
          $messageArray = explode("<div>", $value);
          $messageArray = explode("<br>", $messageArray[1]);
          array_push($message, $messageArray[0]);
        }        
      }
    } else {
      $contentArray = explode("<br>", $contentArray[0]);
      array_push($message, $contentArray[0]);
    }

    $background = Image::make(public_path('uploads/samples/'.$brand.'/'.$backgroundImage));
    $width = $background->width();
    if ($width < 1000) {
      // Resize the picture to insert to the good size
      $background->resize(1000, null, function ($constraint) {
        $constraint->aspectRatio();
      });
    }
    $width = $background->width();
    $height = $background->height();
    
    $background->text('THIS CONTENT IS CREATED BY MEMBER '.$user->customer_id, 30, $height - 30, function($font) {
      $font->file(public_path('fonts/DinPro/DINPro-Cond.otf'));
      $font->size(22);  
      $font->color('#FFFFFF');  
      $font->align('left');
    });
    if ($aligntype == 'left') {
      $width = 30;
    } elseif ($aligntype == 'center') {
      $width = $width / 2;
    } elseif ($aligntype == 'right') {
      $width = $width - 30;
    }
    foreach ($message as $key => $value) {
      $background->text($value, $width, ($key + 1) * 55 + 55, function($font) use ($fontweight, $aligntype) {
        if ($fontweight == 'regular') {
          $font->file(public_path('fonts/DinPro/DINPro-Cond.otf'));
        } elseif ($fontweight == 'medium') {
          $font->file(public_path('fonts/DinPro/DINPro-CondMedium.otf'));
        } elseif ($fontweight == 'bold') {
          $font->file(public_path('fonts/DinPro/DINPro-CondBold.otf'));
        }
        $font->size(55);
        $font->color('#FFFFFF');
        $font->align($aligntype);
      });
    }
    $background->save(public_path('uploads/images/'.$user->customer_id.'/'.$filename));
    
    return response()->json(['filename' => $filename]);
  }

  public function downloadSohoFile(Request $request)
  {
    $user = loggedUser();
    $type = $request->type;
    $brand = $request->brand;
    $brand = explode(".", $brand);
    $location = $request->location;
    $filename = $request->filename;
    $file = '';
    if ($type == 'image') {
      if ($location == 'images') {
        $file = public_path(). '/uploads/images/'.$user->customer_id.'/'.$filename;
      } else {
        $file = public_path(). '/uploads/library/'.$brand[0].'/'.$filename;
      }
    } elseif ($type == 'video') {
      $file = public_path(). '/uploads/videos/'.$brand[0].'/'.$filename;
    }

    return Response::download($file);
  }

  public function shareImage(Request $request)
  {
    $user = loggedUser();
    $brand = $request->brand;
    $brand = explode(".", $brand);
    $filename = $request->filename;
    $fileUrl = $brand[0].'/'.$filename;

    $redirectURL   = route('newsoho'); //Callback URL
    $fbPermissions = array('publish_actions');

    $fb = new Facebook([
      'app_id' => '',
      'app_secret' => '',
      'default_graph_version' => 'v2.10',
      //'default_access_token' => '{access-token}', // optional
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $token = Session::get('facebook_access_token');
    try {
      if(isset($token)){
        $accessToken = Session::get('facebook_access_token');
      }else{
        $accessToken = $helper->getAccessToken();
      }
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if (isset($accessToken)) {
      $token = Session::get('facebook_access_token');
      if (isset($token)) {
        $fb->setDefaultAccessToken($token);
      } else {
        // Put short-lived access token in session
        Session::put('facebook_access_token', (string) $accessToken);
        
        // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        $token = Session::get('facebook_access_token');
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($token);
        Session::put('facebook_access_token', (string) $longLivedAccessToken);
        // Set default access token to be used in script
        $token = Session::get('facebook_access_token');
        $fb->setDefaultAccessToken($token);
      }
        
      //FB post content
      $message = 'Message from office.elysiumnetwork.io website';
      $title = 'Post From Elysium Network';
      $link = 'https://office.elysiumnetwork.io/';
      $description = '';
      $picture = public_path('uploads/images/'.$user->customer_id .'/'.$brand[0].'/'.$filename);
              
      $attachment = array(
        'message' => $message,
        'name' => $title,
        'link' => $link,
        'description' => $description,
        'picture'=>$picture,
      );
      
      try {
        // Post to Facebook
        $fb->post('/daniel.john.92775/feed', $attachment, $accessToken);
        
        // Display post submission status
        echo 'The post was published successfully to the Facebook timeline.';
      }catch(FacebookResponseException $e){
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      }catch(FacebookSDKException $e){
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
    } else {
      // Get Facebook login URL
      $fbLoginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
      // Redirect to Facebook login page
      // return Redirect::to($fbLoginURL);
      return redirect()->away($fbLoginURL);
    }

    // return view('User.newSoho.shareImage')->with(['user'=>$user, 'fileUrl'=>$fileUrl]);
  }

	public function contentLibrary(Request $request)
  {
    $brand = $request->brand;
    if (!isset($brand))
      $brand = 'network';
    $user = loggedUser();
    $fileNames = [];
    $libraryNetworkPath = public_path('uploads/library/network');
    $libraryCapitalPath = public_path('uploads/library/capital');
    $libraryInsiderPath = public_path('uploads/library/insider');
    $libraryNetworkFiles = \File::allFiles($libraryNetworkPath);
    $libraryCapitalFiles = \File::allFiles($libraryCapitalPath);
    $libraryInsiderFiles = \File::allFiles($libraryInsiderPath);
    if ($brand == 'network') {
      $files = $libraryNetworkFiles;
    } elseif ($brand == 'capital') {
      $files = $libraryCapitalFiles;
    } elseif ($brand == 'insider') {
      $files = $libraryInsiderFiles;
    }

    foreach($files as $file) {
      array_push($fileNames, array('library' => pathinfo($file)['filename'].'.'.pathinfo($file)['extension']));
    }
    
    return view('User.newSoho.contentLibrary')->with(['user'=>$user, 'fileNames'=>$fileNames, 'brand'=>$brand, 'files'=>json_encode($fileNames)]);
	}

	public function videoLibrary()
  {
    $user = loggedUser();
    $networkFileNames = [];
    $capitalFileNames = [];
    $insiderFileNames = [];
    $networkPath = public_path('uploads/videos/network');
    $capitalPath = public_path('uploads/videos/capital');
    $insiderPath = public_path('uploads/videos/insider');
    $networkFiles = \File::allFiles($networkPath);
    $capitalFiles = \File::allFiles($capitalPath);
    $insiderFiles = \File::allFiles($insiderPath);

    foreach($networkFiles as $file) {
      array_push($networkFileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }

    foreach($capitalFiles as $file) {
      array_push($capitalFileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }

    foreach($insiderFiles as $file) {
      array_push($insiderFileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }
		return view('User.newSoho.videoLibrary')->with(['user'=>$user, 'networkFileNames'=>$networkFileNames, 'capitalFileNames'=>$capitalFileNames, 'insiderFileNames'=>$insiderFileNames, 'nfiles'=>json_encode($networkFileNames), 'cfiles'=>json_encode($capitalFileNames), 'ifiles'=>json_encode($insiderFileNames)]);
	}

	public function email()
  {
    $user = loggedUser();
		return view('User.newSoho.email')->with(['user'=>$user]);
	}

  public function sendEmail(Request $request)
  {
    $send_email = $request->send_email;
    $bodyMessage = $request->bodyMessage;
    $data = array(
      'email' => $send_email,
      'bodyMessage' => $bodyMessage
    );
    \Mail::to($data['email'])->send(new ClientSendQuote($data));
  }

  public function imageManagement(Request $request)
  {
    $user = loggedUser();
    if ($user->customer_id != 526792)
      return redirect()->route('newsoho');
    $location = $request->location;
    if (!isset($location)) {
      $location = 'samples';
    }
    $fileNames = [];
    $networkFileNames = [];
    $capitalFileNames = [];
    $insiderFileNames = [];
    $networkPath = public_path('uploads/'.$location.'/network');
    $capitalPath = public_path('uploads/'.$location.'/capital');
    $insiderPath = public_path('uploads/'.$location.'/insider');
    $networkFiles = \File::allFiles($networkPath);
    $capitalFiles = \File::allFiles($capitalPath);
    $insiderFiles = \File::allFiles($insiderPath);

    foreach($networkFiles as $file) {
      array_push($networkFileNames, array($location => pathinfo($file)['filename'].'.'.pathinfo($file)['extension']));
    }

    foreach($capitalFiles as $file) {
      array_push($capitalFileNames, array($location => pathinfo($file)['filename'].'.'.pathinfo($file)['extension']));
    }

    foreach($insiderFiles as $file) {
      array_push($insiderFileNames, array($location => pathinfo($file)['filename'].'.'.pathinfo($file)['extension']));
    }

    return view('User.newSoho.imageManagement')->with(['user'=>$user, 'location'=>$location, 'networkFileNames'=>$networkFileNames, 'capitalFileNames'=>$capitalFileNames, 'insiderFileNames'=>$insiderFileNames]);
  }

  public function videoManagement()
  {
    $user = loggedUser();
    if ($user->customer_id != 526792)
      return redirect()->route('newsoho');
    $networkFileNames = [];
    $capitalFileNames = [];
    $insiderFileNames = [];
    $networkPath = public_path('uploads/videos/network');
    $capitalPath = public_path('uploads/videos/capital');
    $insiderPath = public_path('uploads/videos/insider');
    $networkFiles = \File::allFiles($networkPath);
    $capitalFiles = \File::allFiles($capitalPath);
    $insiderFiles = \File::allFiles($insiderPath);

    foreach($networkFiles as $file) {
      array_push($networkFileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }

    foreach($capitalFiles as $file) {
      array_push($capitalFileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }

    foreach($insiderFiles as $file) {
      array_push($insiderFileNames, pathinfo($file)['filename'].'.'.pathinfo($file)['extension']);
    }
    return view('User.newSoho.videoManagement')->with(['user'=>$user, 'networkFileNames'=>$networkFileNames, 'capitalFileNames'=>$capitalFileNames, 'insiderFileNames'=>$insiderFileNames]);
  }

  public function storeBrand(Request $request) {
    $brand = $request->brand;
    if (isset($brand))
      Session::put('brand', $brand);
    $location = $request->location;
    if (isset($location))
      Session::put('location', $location);
    return response()->json(['success' => 'save the brand and location']);
  }

  public function uploadImage(Request $request)
  {
    $location = Session::get('location');
    $brand = Session::get('brand');
    $path = public_path('uploads/'.$location.'/'.$brand);

    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }

    $file = $request->file('file');
    if (!isset($file)) {
      return redirect()->back();
    } else {
      $currentDateTime = new DateTime();
      $currentDateTime = $currentDateTime->getTimestamp();

      $name = $currentDateTime . uniqid() .'.'. $file->getClientOriginalExtension();

      $file->move($path, $name);
    }
  }

  public function uploadVideo(Request $request)
  {
    $brand = Session::get('brand');
    $path = public_path('uploads/videos/'.$brand);
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }

    $file = $request->file('file');
    if (!isset($file)) {
      return redirect()->back();
    } else {
      $currentDateTime = new DateTime();
      $currentDateTime = $currentDateTime->getTimestamp();

      $name = $currentDateTime . uniqid() .'.'. $file->getClientOriginalExtension();

      $file->move($path, $name);
    }
  }

  public function removeFile(Request $request)
  {
    $type = $request->type;
    $brand = $request->brand;
    $location = $request->location;
    $filename = $request->filename;
    $src = '';
    if ($type == 'image') {
      $src = 'uploads/'.$location.'/'.$brand.'/'.$filename;
    } elseif ($type == 'video') {
      $src = 'uploads/videos/'.$brand.'/'.$filename;
    }
    if(File::exists(public_path($src))){
      File::delete(public_path($src));
      return redirect()->back();
    }else{
      return redirect()->back()->with('error', __('File does not exists.'));
    }
  }
}