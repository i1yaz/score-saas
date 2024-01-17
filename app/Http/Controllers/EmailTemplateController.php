<?php

namespace App\Http\Controllers;

use App\Mail\SessionSubmittedMail;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\MailTemplates\Models\MailTemplate;
use Faker\Factory as Faker;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = MailTemplate::paginate(10);
        return view('email_templates.index',compact('templates'));
    }
    public function show($template)
    {
        $template = MailTemplate::findOrFail($template);
        return view('email_templates.show',compact('template'));
    }
    public function edit($template)
    {
        $template = MailTemplate::findOrFail($template);
        $variables = $template->mailable::getVariables();
        return view('email_templates.edit',compact('template','variables'));
    }
    public function update($template)
    {
        request()->validate([
            'name' => 'required',
            'subject' => 'required',
            'html_template' => 'required'
        ]);

        $template = MailTemplate::findOrFail($template);
        $input = request()->all();
        $input['text_template'] = strip_tags($input['html_template']);
        unset($input['files']);
        $template->update($input);
        return redirect()->route('email-templates.index')->with('success','Email template updated successfully');
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
        $tenant = getCurrentTenant();
        $imageName = time().'.' . $image->getClientOriginalExtension();
        $path = "pictures/email-images/tenant-{$tenant}/{$request->template_id}";
        storeFile($path,$image,$imageName);
        return response()->json(['url' => asset("{$path}/{$imageName}")]);
    }

    public function send(Request $request,$template_id)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $template = MailTemplate::findOrFail($template_id);
        $data = $this->getEmailData($template_id);
        $mailable = $template->mailable;
        Mail::to($request->email)->send(new $mailable($data));
        return redirect()->route('email-templates.index')->with('success','Email sent successfully');
    }
    public function getEmailData($template_id){
        $faker = Faker::create($template_id);
        $data = [];
        if ($template_id==1){
            $data['invoice_id'] = $faker->randomNumber(5);
            $data['payment_gateway_id'] ='Stripe' ;
            $data['transaction_id'] = 'stripe_id'. $faker->randomNumber(5);
            $data['amount'] = $faker->randomFloat(2, 0, 1000);
        }
        if ($template_id==2){
            $data['first_name'] = $faker->firstName;
            $data['last_name'] = $faker->lastName;
            $data['email'] = $faker->email;
        }
        if ($template_id==3){
            $data['package'] = $faker->randomNumber(5);
        }
        if (in_array($template_id,[4,5,6,7,8])){
            return [];
        }

        if ($template_id==9){
            $data['time'] = "1h:30m";
            $data['student_email'] = $faker->email;
            $data['student_first_name'] =$faker->firstName;
            $data['student_last_name'] = $faker->lastName;
            $data['bill_amount'] = $faker->randomFloat(2, 0, 1000);
            $data['start_time'] = Carbon::now()->format('Y-m-d');
        }
        return $data;
    }
}
