<?php

namespace App\Http\Controllers\Admin;

use DateTimeZone;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $settings;
    public $user;

    public function __construct(Setting $settings)
    {
        $this->settings     = $settings;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    // Setting
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.settings.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Settings';
        $data['settings'] = Setting::first();
        return view('admin.settings', $data);
    }

    // Update Setting
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $setting = Setting::first();

            if ($request->hasFile('site_logo')) {
                $file = $request->file('site_logo');
                $filename = uniqid().".".$file->getClientOriginalExtension();
                $file->move(public_path('uploads/logo'), $filename);
                $setting->site_logo = 'uploads/logo/'.$filename;
            }
             if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $filename = uniqid().".".$file->getClientOriginalExtension();
                $file->move(public_path('uploads/favicon'), $filename);
                $setting->favicon = 'uploads/favicon/'.$filename;
            }

            $setting->site_name                         = $request->site_name;
            $setting->primary_color                     = $request->primary_color;
            $setting->secondary_color                   = $request->secondary_color;
            $setting->smtp_host                         = $request->smtp_host;
            $setting->smtp_port                         = $request->smtp_port;
            $setting->smtp_username                     = $request->smtp_username;
            $setting->smtp_address                      = $request->smtp_address;
            $setting->azure_ocr_endpoint                = $request->azure_ocr_endpoint;
            $setting->azure_ocr_key                     = $request->azure_ocr_key;
            $setting->azure_openai_endpoint             = $request->azure_openai_endpoint;
            $setting->azure_openai_key                  = $request->azure_openai_key;
            $setting->azure_openai_deployment           = $request->azure_openai_deployment;
            $setting->system_prompt                     = $request->system_prompt;
            $setting->user_prompt                       = $request->user_prompt;
            $setting->system_prompt_2                   = $request->system_prompt_2;
            $setting->user_prompt_2                     = $request->user_prompt_2;
            $setting->rev_perc                          = $request->rev_perc ?? 0;
            $setting->asset_perc                        = $request->asset_perc ?? 0;
            $setting->profit_perc                       = $request->profit_perc ?? 0;

            // Global default materiality thresholds for new tenants
            $setting->revenue_min_default                = $request->input('revenue_min_default');
            $setting->revenue_max_default                = $request->input('revenue_max_default');
            $setting->asset_min_default                  = $request->input('asset_min_default');
            $setting->asset_max_default                  = $request->input('asset_max_default');
            $setting->profit_min_default                 = $request->input('profit_min_default');
            $setting->profit_max_default                 = $request->input('profit_max_default');
            $setting->performance_materiality_default    = $request->input('performance_materiality_default');
            $setting->trivial_threshold_default          = $request->input('trivial_threshold_default');
            $setting->maintenance_mode                   = $request->maintenance_mode ?? 0;
            $setting->stripe_publishable_key             = $request->stripe_publishable_key;
            $setting->stripe_secret                      = $request->stripe_secret;
            $setting->email                              = $request->email;
            $setting->support_email                      = $request->support_email;
            $setting->phone_no                           = $request->phone_no;
            $setting->office_address                     = $request->office_address;
            $setting->business_hours                     = $request->business_hours;
            $setting->seo_meta_title                     = $request->seo_meta_title;
            $setting->seo_meta_description               = $request->seo_meta_description;
            $setting->seo_keywords                       = $request->seo_keywords;
            $setting->twitter_url                        = $request->twitter_url;
            $setting->linkedin_url                       = $request->linkedin_url;
            $setting->save();

            DB::table('config')->where('config_key', 'stripe_publishable_key')
                ->update(['config_value' => $request->stripe_publishable_key]);

            DB::table('config')->where('config_key', 'stripe_secret')
                ->update(['config_value' => $request->stripe_secret]);

            DB::commit();

            return redirect()->back()
                ->with('notify', [['success', 'Settings updated successfully!']]);

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()
                ->with('notify', [['error', 'Something went wrong. Please try again']]);
        }
    }

}
