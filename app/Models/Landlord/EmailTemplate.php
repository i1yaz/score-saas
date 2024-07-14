<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $lang to match to language
 * @property string|null $type everyone|admin|client
 * @property string|null $category users|projects|tasks|leads|tickets|billing|estimates|other|system
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $variables
 * @property string|null $created
 * @property string|null $updated
 * @property string $status enabled|disabled
 * @property string|null $language
 * @property string $real_template yes|no
 * @property string $show_enabled yes|no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|EmailTemplate active()
 * @method static Builder|EmailTemplate inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereRealTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereShowEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate whereVariables($value)
 * @mixin \Eloquent
 */
class EmailTemplate  extends BaseModel
{
    public function parse($section = 'body', $data) {

        //validate
        if (!is_array($data) || !in_array($section, ['body', 'subject'])) {
            return $this->body;
        }

        //set the content
        if ($section == 'body') {
            $content = $this->body;
        } else {
            $content = $this->subject;
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
