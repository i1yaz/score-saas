<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends BaseModel
{
    protected $guarded = ['id'];

    const RESTRICTED_SUBDOMAINS = ['admin', 'administrator', 'ads', 'webmail', 'mail', 'smtp', 'pop', 'www', 'blog', 'blogs', 'forums', 'store', 'help', 'status', 'doc', 'docs', 'api', 'support', 'contact', 'news', 'press', 'about', 'careers', 'terms', 'privacy', 'legal', 'copyright', 'faq', 'help', 'info','ssl', 'dns', 'cpanel', 'whm', 'ssh', 'ftp', 'vpn',
        'autodiscover', 'autoconfig', 'webdisk', 'webmail', 'ns1', 'ns2', 'ns3', 'ns4', 'ns5', 'ns6', 'secure', 'demo', 'm', 'mail', 'webmaster', 'host', 'hosting', 'admin',
        'chat', 'calendar', 'projects', 'tasks', 'integrations','xxx','sex','porn','adult','ns','nsfw'];
}
