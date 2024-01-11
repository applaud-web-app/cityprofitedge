<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\DeviceToken;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Constants\Status;

class SiteController extends Controller
{
    public function index(){
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }
        $pageTitle = 'Home';
       
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','/')->first();
        return view($this->activeTemplate . 'home', compact('pageTitle','sections'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','contact')->first();
        return view($this->activeTemplate . 'contact',compact('pageTitle', 'sections', 'user'));
    }


    public function contactSubmit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = $ticket->status = Status::TICKET_OPEN;;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug,$id)
    {
        $policy = Frontend::where('id',$id)->where('data_keys','policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        return view($this->activeTemplate.'policy',compact('policy','pageTitle'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs(){
        $pageTitle = 'Blogs';
        $blogs = Frontend::where('data_keys', 'blog.element')->where('template_name', activeTemplateName())->orderBy('id', 'DESC')->paginate(getPaginate());
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug','blogs')->first();
        return view($this->activeTemplate.'blogs',compact('pageTitle', 'blogs', 'sections'));
    }

    public function blogDetails($slug, $id){

        $pageTitle = 'Blog Details';
        $blog = Frontend::where('id', $id)->where('data_keys','blog.element')->firstOrFail();
        $latestBlogs = Frontend::where('data_keys','blog.element')->where('id', '!=', $blog->id)->where('template_name', activeTemplateName())->orderBy('id', 'DESC')->take(10)->get();

        $customPageTitle                   = $blog->data_values->title;
        $seoContents['keywords']           = $blog->meta_keywords ?? [];
        $seoContents['social_title']       = $blog->data_values->title;
        $seoContents['description']        = strLimit(strip_tags($blog->data_values->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($blog->data_values->description), 150);
        $seoContents['image']              = getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '855x480');
        $seoContents['image_size']         = '855x480';

        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle', 'latestBlogs', 'seoContents', 'customPageTitle'));
    }

    public function cookieAccept(){
        Cookie::queue('gdpr_cookie',gs('site_name') , 43200);
        return back();
    }

    public function cookiePolicy(){
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys','cookie.data')->first();
        return view($this->activeTemplate.'cookie',compact('pageTitle','cookie'));
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/RobotoMono-Regular.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if(gs('maintenance_mode') == Status::DISABLE){
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys','maintenance.data')->first();
        return view($this->activeTemplate.'maintenance',compact('pageTitle','maintenance'));
    }

    public function subscribe(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:250|unique:subscribers,email'
        ]);

        if(!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $newSubscriber = new Subscriber();
        $newSubscriber->email = $request->email;
        $newSubscriber->save();

        return response()->json(['success'=>true, 'message'=>'Thank you, We\'ll notice you our latest news']);
    }

    public function packages(){
        $pageTitle = 'Packages';
        $extends = Auth::user() ? $this->activeTemplate.'layouts.master' : $this->activeTemplate.'layouts.frontend';
        $sections = Page::where('tempname', $this->activeTemplate)->where('slug','packages')->first();
        return view($this->activeTemplate.'package',compact('pageTitle', 'extends','sections'));
    }

    public function getDeviceToken(Request $request){

        $validator = Validator::make($request->all(), [
            'token'=> 'required'
        ]);

        if($validator->fails()){
            return ['success'=>false, 'errors'=>$validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if($deviceToken){
            return ['success'=>true, 'message'=>'Already exists'];
        }

        $deviceToken = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token = $request->token;
        $deviceToken->is_app = 0;
        $deviceToken->save();

        return ['success'=>true, 'message'=>'Token save successfully'];
    }

}
