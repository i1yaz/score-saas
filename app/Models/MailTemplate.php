<?php

namespace App\Models;

use Spatie\MailTemplates\Models\MailTemplate as SpatieMailTemplate;

/**
 * 
 *
 * @property-read array $variables
 * @method static Builder|MailTemplate forMailable(\Illuminate\Contracts\Mail\Mailable $mailable)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate query()
 * @mixin \Eloquent
 */
class MailTemplate extends SpatieMailTemplate
{

    protected $casts = [
        'status' => 'boolean'
    ];
}
