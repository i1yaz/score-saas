<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $variables['template'] = explode(',', $template->variables);
        $variables['general'] = explode(',', config('system.email_general_variables'));
        $html = view('landlord.settings.email_templates_form', compact('template','variables'))->render();
        return response()->json(['html' => $html]);
    }
    public function updateTemplate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        EmailTemplate::where('id', $id)->update([
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
        return response()->json(['message' => 'Email template updated successfully']);
    }
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|size:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $path = "pictures/app-admin/email-images/{$request->template_id}";
        storeFile($path, $image, $imageName);
        return response()->json(['url' => asset("{$path}/{$imageName}")]);
    }

}
