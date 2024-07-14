<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmtpController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function show()
    {
        $settings = Setting::where('id', 'default')->first();
        return view('landlord.settings.smtp_settings',compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_smtp_host' => ['required'],
            'email_smtp_port' => ['required', 'numeric'],
            'email_smtp_username' => ['nullable'],
            'email_smtp_password' => ['nullable'],
            'email_smtp_encryption' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        Setting::where('id', 'default')->update([
            'email_smtp_host' => $request->email_smtp_host,
            'email_smtp_port' => $request->email_smtp_port,
            'email_smtp_username' => empty($request->email_smtp_username)?null:$request->email_smtp_username,
            'email_smtp_password' => empty($request->email_smtp_password)?null:$request->email_smtp_password,
            'email_smtp_encryption' => empty($request->email_smtp_encryption)?null:$request->email_smtp_encryption,
        ]);
        return response()->json(['message' => 'SMTP settings updated successfully']);
    }
}
