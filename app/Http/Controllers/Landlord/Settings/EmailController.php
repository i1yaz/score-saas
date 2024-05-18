<?php

namespace App\Http\Controllers\Landlord\Settings;

use App\Http\Controllers\Controller;
use App\Http\Responses\Landlord\Settings\Email\TestEmailResponse;
use App\Http\Responses\Landlord\Settings\Emaillog\LogResponse;
use App\Mail\Landlord\Admin\TestEmail;
use App\Models\Landlord\EmailLog;
use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }
    public function show()
    {
        $settings = Setting::Where('id', 'default')->first();
        return view('landlord.settings.email_settings',compact('settings'));
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_from_address' => 'required',
            'email_from_name' => 'required',
            'email_server_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        Setting::where('id', 'default')->update([
            'email_from_address' => $request->email_from_address,
            'email_from_name' => $request->email_from_name,
            'email_server_type' => $request->email_server_type,
        ]);
        return response()->json(['message' => 'Email settings updated successfully']);
    }

    public function testEmail() {

        $settings = Setting::Where('id', 'default')->first();

    }

    public function testEmailAction(Request $request) {

        $validator = Validator::make($request->all(), [
            'email_to_address' => ['required', 'email']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $data = [
            'notification_subject' => __('lang.email_delivery_test'),
            'notification_title' => __('lang.email_delivery_test'),
            'notification_message' => __('lang.email_delivery_this_is_a_test'),
            'first_name' => auth()->user()->first_name,
            'email' => $request->email_to_address,
        ];
        $mail = new TestEmail($data);
        $mail->build();

        return response()->json(['message' => 'Test email sent successfully']);
    }

    public function logShow() {

        $emails = EmailLog::query()
            ->orderBy('emaillog_id', 'DESC')
            ->paginate(config('system.settings_system_pagination_limits'));

        //payload
        $payload = [
            'page' => $this->pageSettings('log'),
            'emails' => $emails,
        ];

        //show the view
        return new LogResponse($payload);
    }

    /**
     * Show the email
     * @return blade view | ajax view
     */
    public function logRead($id) {

        if (!$email = EmailLog::Where('emaillog_id', $id)->first()) {
            abort(404);
        }

        //page
        $html = view('landlord/settings/sections/emaillog/read', compact('email'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html,
        ];

        //fix emailfooer
        $jsondata['dom_classes'][] = [
            'selector' => 'style',
            'action' => 'remove',
            'value' => 'footer',
        ];

        //remove <style> tags
        $jsondata['dom_visibility'][] = [
            'selector' => '.settings-email-view-wrapper > style',
            'action' => 'hide-remove',
        ];

        //render
        return response()->json($jsondata);
    }

    /**
     * delete a record
     *
     * @return \Illuminate\Http\Response
     */
    public function logDelete($id) {

        if (!$email = EmailLog::Where('emaillog_id', $id)->first()) {
            abort(404);
        }

        //delete record
        $email->delete();

        //remove table row
        $jsondata['dom_visibility'][] = array(
            'selector' => '#email_' . $id,
            'action' => 'slideup-slow-remove',
        );

        //success
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }

    /**
     * delete all emails in the log
     *
     * @return \Illuminate\Http\Response
     */
    public function logPurge() {

        //delete all rows
        EmailLog::getQuery()->delete();

        //remove all rows
        $jsondata['dom_visibility'][] = array(
            'selector' => '.settings-each-email',
            'action' => 'hide',
        );
        $jsondata['dom_visibility'][] = array(
            'selector' => '.loadmore-button-container',
            'action' => 'hide',
        );

        //success
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }
}
