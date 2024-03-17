<?php

namespace App\Models;

use Spatie\MailTemplates\Models\MailTemplate as SpatieMailTemplate;

class MailTemplate extends SpatieMailTemplate
{
    protected $casts = [
        'status' => 'boolean'
    ];
}
