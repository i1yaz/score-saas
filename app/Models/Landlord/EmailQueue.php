<?php

namespace App\Models\Landlord;

use App\Models\BaseModel;

/**
 *
 *
 * @property int $id
 * @property string|null $created
 * @property string|null $updated
 * @property string|null $to
 * @property string|null $from_email optional (used in sending client direct email)
 * @property string|null $from_name optional (used in sending client direct email)
 * @property string|null $subject
 * @property string|null $message
 * @property string $type general|pdf (used for emails that need to generate a pdf)
 * @property string|null $attachments
 * @property string|null $resource_type e.g. invoice. Used mainly for deleting records, when resource has been deleted
 * @property int|null $resource_id
 * @property string|null $pdf_resource_type estimate|invoice
 * @property int|null $pdf_resource_id resource id (e.g. estimate id)
 * @property string|null $started_at timestamp of when processing started
 * @property string $status new|processing (set to processing by the cronjob, to avoid duplicate processing)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|EmailQueue active()
 * @method static Builder|EmailQueue inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereFromEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereFromName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue wherePdfResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue wherePdfResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailQueue extends BaseModel
{
    protected $fillable = ['status','started_at'];

}
