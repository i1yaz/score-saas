<?php

namespace App\Http\Controllers;

use Spatie\MailTemplates\Models\MailTemplate;

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
        $template = MailTemplate::findOrFail($template);
        $template->update(request()->validate([
            'name' => 'required',
            'subject' => 'required',
            'html_template' => 'required',
            'text_template' => 'required',
        ]));
        return redirect()->route('email_templates.index')->with('success','Email template updated successfully');
    }
}
