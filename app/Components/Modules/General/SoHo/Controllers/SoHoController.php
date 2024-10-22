<?php
/**
 *  -------------------------------------------------
 *  RTCLab sp. z o.o.  Copyright (c) 2019 All Rights Reserved
 *  -------------------------------------------------
 *
 * @author Christopher Milkowski, Arthur Milkowski
 * @link https://www.livewebinar.com
 * @see https://www.livewebinar.com
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Components\Modules\General\SoHo\Controllers;


use Auth;
use App\Components\Modules\General\SoHo\SoHoIndex as Module;
use App\Components\Modules\General\SoHo\ModuleCore\Traits\Validations;
use App\Components\Modules\General\SoHo\ModuleCore\Eloquents\TellFriendMails;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Eloquents\Retortal;
use App\Mail\ClientSendQuote;
use App\Mail\MailTemplate;
use Carbon\Carbon;
use Response;
use PDF;
use File;
use Image;
use DateTime;
use Mail;

/**
 * Class SoHoController
 * @package App\Components\Modules\General\SoHo\Controllers
 */
class SoHoController extends Controller
{
    use Validations;

    private $module;

    /**
     * __construct function
     */
    public function __construct()
    {
        parent::__construct();
        $this->module = app()->make(Module::class);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */

    public function index(Request $request)
    {
        session('theScope') ?: 'user';

        if (session('theScope') && is_string(session('theScope'))) {
            $user = Auth::user();
            if (!isset($user))
            {
                return redirect()->route('user.login');
            } else {
                if ($user->package->slug == 'affiliate' || $user->package->slug == 'client') {
                    return view('General.SoHo.Views.noaccess');
                }
            }
        } else {
            if (isAdmin()) {
                return redirect()->route('admin.login');
            } else {
                return redirect()->route('user.login');
            }
        }
        $user = loggedUser();

        if (!file_exists('uploads/images/'.$user->customer_id))
        {
          mkdir('uploads/images/'.$user->customer_id, 0777 , true);
        }

        $brand = $request->brand;
        if (!isset($brand)) {
          $brand = 'network';
        }

        $fileNames = [];
        $url = 'uploads/samples/'.$brand.'/';
        $path = public_path($url);

        $files = \File::allFiles($path);

        foreach($files as $file) {
          $fileNames[] = [
            'location' => $brand,
            'name' => pathinfo($file)['basename'],
            'src' => $url . pathinfo($file)['basename']
          ];
        }
    
        return view('General.SoHo.Views.contentCreator')->with(['user'=>$user, 'fileNames'=>$fileNames, 'brand'=>$brand, 'files'=>json_encode($fileNames)]);
    }

    public function changeString(Request $request)
    {
        $content = $request->content;
        $array = explode("\n", $content);
        return response()->json(['content' => $array]);
    }

    public function makeSohoImage(Request $request)
    {
        $user = loggedUser();
        $backgroundImage = $request->backgroundImage;
        $backgroundImageArray = explode(".", $backgroundImage);
        $filename = $user->customer_id . '.' . $backgroundImageArray[1];
        $brand = $request->brand;
        $header_content = $request->header_content;
        $tagline_content = $request->tagline_content;
        $header_message = explode("\n", $header_content);
        $tagline_message = explode("\n", $tagline_content);

        $url = public_path('/uploads/samples/'.$brand.'/'.$backgroundImage);

        $background = Image::make($url);
        $width = $background->width();
        if ($width < 1200) {
          // Resize the picture to insert to the good size
          $background->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
          });
        }
        $width = $background->width();
        $height = $background->height();
        
        $background->text($user->customer_id, $width - 30, $height / 2, function($font) {
          $font->file(public_path('fonts/DinPro/DINPro-Cond.otf'));
          $font->size(22);
          $font->color('rgba(255, 255, 255, 0.35)');
            $font->align('right');
            $font->valign('center');
            $font->angle(90);
        });

        $width = 110;
        $height = 90;
        foreach ($header_message as $value) {
            $fontweight = 96;
            $aligntype = 'left';
            $lines = explode("\n", wordwrap($value, 25)); // break line after 25 characters
            for ($i = 0; $i < count($lines); $i++) {
                $height = 93 + $height;
                $background->text($lines[$i], $width, $height, function($font) use ($fontweight, $aligntype) {
                    $font->file(public_path('fonts/DinPro/DINPro-CondBold.otf'));
                    $font->size($fontweight);
                    $font->color('#FFFFFF');
                    $font->align($aligntype);
                });
            }
        }
        if (count($header_message) > 0) {
            $height = $height + 36;
        } else {
            $height = 132;
        }

        foreach ($tagline_message as $value) {
            $fontweight = 50;
            $aligntype = 'left';
            $lines = explode("\n", wordwrap($value, 55)); // break line after 55 characters
            for ($i = 0; $i < count($lines); $i++) {
                $height = $height + 60;
                $background->text($lines[$i], $width, $height, function($font) use ($fontweight, $aligntype) {
                    $font->file(public_path('fonts/DinPro/DINPro-CondMedium.otf'));
                    $font->size($fontweight);
                    $font->color('#FFFFFF');
                    $font->align($aligntype);
                });
            }
        }
        $background->save(public_path('uploads/images/'.$user->customer_id.'/'.$filename));
        
        return response()->json(['filename' => $filename]);
    }

    public function downloadSohoFile(Request $request)
    {
        $user = loggedUser();
        $type = $request->type;
        $brand = $request->brand;
        $location = $request->location;
        $filename = $request->filename;
        $headers = [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'. $filename .'"',
        ];
        $file = '';
        if ($type == 'image') {
          if ($location == 'images') {
            $file = public_path(). '/uploads/images/'.$user->customer_id.'/'.$filename;
            return Response::download($file);
          } else {
            $file = 'uploads/library/'.$brand.'/'.$filename;
          }
        } elseif ($type == 'video') {
          $file = 'uploads/videos/'.$brand.'/'.$filename;
        }

        return Response::download($file, $filename, $headers);
    }

    public function contentLibrary(Request $request)
    {
        session('theScope') ?: 'user';

        if (session('theScope') && is_string(session('theScope'))) {
            $user = Auth::user();
            if (!isset($user))
            {
                return redirect()->route('user.login');
            } else {
                if ($user->package->slug == 'affiliate' || $user->package->slug == 'client') {
                    return view('General.SoHo.Views.noaccess');
                }
            }
        } else {
            if (isAdmin()) {
                return redirect()->route('admin.login');
            } else {
                return redirect()->route('user.login');
            }
        }
        $brand = $request->brand;
        if (!isset($brand))
          $brand = 'network';
        $user = loggedUser();

        $fileNames = [];
        $url = 'uploads/library/'.$brand.'/';
        $path = public_path($url);

        $files = \File::allFiles($path);

        foreach($files as $file) {
          $fileNames[] = [
            'location' => $brand,
            'name' => pathinfo($file)['basename'],
            'src' => $url . pathinfo($file)['basename']
          ];
        }
        
        return view('General.SoHo.Views.contentLibrary')->with(['user'=>$user, 'fileNames'=>$fileNames, 'brand'=>$brand, 'files'=>json_encode($fileNames)]);
    }

    public function videoLibrary()
    {
        session('theScope') ?: 'user';

        if (session('theScope') && is_string(session('theScope'))) {
            $user = Auth::user();
            if (!isset($user))
            {
                return redirect()->route('user.login');
            } else {
                if ($user->package->slug == 'affiliate' || $user->package->slug == 'client') {
                    return view('General.SoHo.Views.noaccess');
                }
            }
        } else {
            if (isAdmin()) {
                return redirect()->route('admin.login');
            } else {
                return redirect()->route('user.login');
            }
        }

        $user = loggedUser();

        $networkFileNames = [];
        $capitalFileNames = [];
        $insiderFileNames = [];
        $networkPath = 'uploads/videos/network/';
        $capitalPath = 'uploads/videos/capital/';
        $insiderPath = 'uploads/videos/insider/';
        $networkFiles = \File::allFiles(public_path($networkPath));
        $capitalFiles = \File::allFiles(public_path($capitalPath));
        $insiderFiles = \File::allFiles(public_path($insiderPath));

        foreach($networkFiles as $file) {
          $networkFileNames[] = [
            'name' => pathinfo($file)['basename'],
            'src' => $networkPath . pathinfo($file)['basename']
          ];
        }

        foreach($capitalFiles as $file) {
          $capitalFileNames[] = [
            'name' => pathinfo($file)['basename'],
            'src' => $capitalPath . pathinfo($file)['basename']
          ];
        }

        foreach($insiderFiles as $file) {
          $insiderFileNames[] = [
            'name' => pathinfo($file)['basename'],
            'src' => $insiderPath . pathinfo($file)['basename']
          ];
        }

        return view('General.SoHo.Views.videoLibrary')->with(['user'=>$user, 'networkFileNames'=>$networkFileNames, 'capitalFileNames'=>$capitalFileNames, 'insiderFileNames'=>$insiderFileNames, 'nfiles'=>json_encode($networkFileNames), 'cfiles'=>json_encode($capitalFileNames), 'ifiles'=>json_encode($insiderFileNames)]);
    }

    public function email(Request $request)
    {
        session('theScope') ?: 'user';

        if (session('theScope') && is_string(session('theScope'))) {
            $user = Auth::user();
            if (!isset($user))
            {
                return redirect()->route('user.login');
            } else {
                if ($user->package->slug == 'affiliate' || $user->package->slug == 'client') {
                    return view('General.SoHo.Views.noaccess');
                }
            }
        } else {
            if (isAdmin()) {
                return redirect()->route('admin.login');
            } else {
                return redirect()->route('user.login');
            }
        }
        $brand = $request->brand;
        if (!isset($brand))
          $brand = 'network';
        $user = loggedUser();
        return view('General.SoHo.Views.email')->with(['user'=>$user, 'brand'=>$brand]);
    }

    public function sendEmail(Request $request)
    {
        $user = loggedUser();
        $send_email = $request->send_email;
        $from_name = $request->from_name;
        $to_name = $request->to_name;
        $brand = $request->brand;

        $tellFriendMail = new TellFriendMails();
        $tellFriendMail->customer_id = $user->customer_id;
        $tellFriendMail->sender = $from_name;
        $tellFriendMail->receiver = $to_name;
        $tellFriendMail->to = $send_email;
        $tellFriendMail->brand = $brand;
        $tellFriendMail->expiry_date = date('Y-m-d', strtotime(date('Y-m-d') . ' + 5days'));
        $tellFriendMail->save();
        $subject = 'Elysium Friends';
        $data = array(
          'email' => $send_email,
          'from_name' => $from_name,
          'to_name' => $to_name,
          'subject' => $subject,
          'customer_id' => $user->customer_id,
          'tId' => $tellFriendMail->id,
          'brand' => $brand,
          'times' => 1
        );
        $mailData = collect(
            [
                'mailBody' => view('General.SoHo.Views.Partials.FirstMail', $data),
                'attachment' => null,
                'subject' => $subject,
            ]
        );
        \Mail::to($data['email'])->send(new mailTemplate($mailData));
    }

    public function imageManagement(Request $request)
    {
        $user = loggedUser();
        if ($user->customer_id != 526792)
          return redirect()->route('user.soho');
        $location = $request->location;
        if (!isset($location)) {
          $location = 'samples';
        }

        $url = '/';
        $networkFileNames = [];
        $capitalFileNames = [];
        $insiderFileNames = [];
        $networkPath = 'uploads/'.$location.'/network/';
        $capitalPath = 'uploads/'.$location.'/capital/';
        $insiderPath = 'uploads/'.$location.'/insider/';

        $networkFiles = Storage::disk('s3')->files($networkPath);
        $capitalFiles = Storage::disk('s3')->files($capitalPath);
        $insiderFiles = Storage::disk('s3')->files($insiderPath);

        foreach($networkFiles as $file) {
          $networkFileNames[] = [
            'location' => $location,
            'name' => str_replace($networkPath, '', $file),
            'src' => $url . $file
          ];
        }

        foreach($capitalFiles as $file) {
          $capitalFileNames[] = [
            'location' => $location,
            'name' => str_replace($capitalPath, '', $file),
            'src' => $url . $file
          ];
        }

        foreach($insiderFiles as $file) {
          $insiderFileNames[] = [
            'location' => $location,
            'name' => str_replace($insiderPath, '', $file),
            'src' => $url . $file
          ];
        }

        return view('General.SoHo.Views.imageManagement')->with(['user'=>$user, 'location'=>$location, 'networkFileNames'=>$networkFileNames, 'capitalFileNames'=>$capitalFileNames, 'insiderFileNames'=>$insiderFileNames]);
    }

    public function videoManagement()
    {
        $user = loggedUser();
        if ($user->customer_id != 526792)
          return redirect()->route('user.soho');

        $url = '/';

        $networkFileNames = [];
        $capitalFileNames = [];
        $insiderFileNames = [];
        $networkPath = 'uploads/videos/network/';
        $capitalPath = 'uploads/videos/capital/';
        $insiderPath = 'uploads/videos/insider/';
        $networkFiles = Storage::disk('s3')->files($networkPath);
        $capitalFiles = Storage::disk('s3')->files($capitalPath);
        $insiderFiles = Storage::disk('s3')->files($insiderPath);

        foreach($networkFiles as $file) {
          $networkFileNames[] = [
            'name' => str_replace($networkPath, '', $file),
            'src' => $url . $file
          ];
        }

        foreach($capitalFiles as $file) {
          $capitalFileNames[] = [
            'name' => str_replace($capitalPath, '', $file),
            'src' => $url . $file
          ];
        }

        foreach($insiderFiles as $file) {
          $insiderFileNames[] = [
            'name' => str_replace($insiderPath, '', $file),
            'src' => $url . $file
          ];
        }

        return view('General.SoHo.Views.videoManagement')->with(['user'=>$user, 'networkFileNames'=>$networkFileNames, 'capitalFileNames'=>$capitalFileNames, 'insiderFileNames'=>$insiderFileNames]);
    }

    public function storeBrand(Request $request)
    {
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

          $filePath = 'uploads/'.$location.'/'.$brand . '/' . $name;
          Storage::disk('s3')->put($filePath, file_get_contents($file));
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

          $filePath = 'uploads/videos/' . $brand . '/' . $name;
          Storage::disk('s3')->put($filePath, file_get_contents($file));
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
        Storage::disk('s3')->delete($src);
        return redirect()->back();
    }

    public function shareImage(Request $request)
    {
        var_dump('ok');
    }
}
