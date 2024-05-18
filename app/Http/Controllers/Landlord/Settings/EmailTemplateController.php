<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Setting;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function show()
    {
        //get settings
        $settings = Setting::Where('id', 'default')->first();
        //templates by types
        $customer = EmailTemplate::select(['id','name'])->where('category', 'customer')->orderBy('id')->get()->toArray();
        $admin = EmailTemplate::select(['id','name'])->where('category', 'admin')->orderBy('id')->get()->toArray();
        $other = EmailTemplate::select(['id','name'])->where('category', 'other')->orderBy('id')->get()->toArray();
        $system = EmailTemplate::select(['id','name'])->where('category', 'system')->orderBy('id')->get()->toArray();
        $templates = [
            'Select A Template',
            'Customer' => twoIndexArrayToKeyValue($customer,'id','name'),
            'Admin' => twoIndexArrayToKeyValue($admin,'id','name'),
            'Other' => twoIndexArrayToKeyValue($other,'id','name'),
            'System' => twoIndexArrayToKeyValue($system,'id','name'),
        ];
        return view('landlord.settings.email_templates', compact('settings', 'templates'));
    }
    public function showTemplate($id)
    {
        $template = EmailTemplate::where('id', $id)->firstOrFail();
        $html = view('landlord.settings.email_templates_form', compact('template'))->render();
        return view('landlord.settings.email_template', compact('template'));
    }

}
