<?php

namespace App\Models\Web;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Deed extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'content'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function webSite(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'web_site_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return Carbon::parse($date)->toFormattedDayDateString() ;
    }
}
