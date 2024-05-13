<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;

class EmailTemplate  extends BaseModel
{
    public function parse($section = 'body', $data) {

        //validate
        if (!is_array($data) || !in_array($section, ['body', 'subject'])) {
            return $this->emailtemplate_body;
        }

        //set the content
        if ($section == 'body') {
            $content = $this->emailtemplate_body;
        } else {
            $content = $this->emailtemplate_subject;
        }

        //parse the content and inject actual data
        $parsed = preg_replace_callback('/{(.*?)}/', function ($matches) use ($data) {
            list($shortcode, $index) = $matches;
            //if shortcode is found, replace or return as is
            if (isset($data[$index])) {
                return $data[$index];
            } else {
                return $shortcode;
            }
        }, $content);

        //return
        return $parsed;
    }
}
