<?php

namespace App\Models\Web;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\UuidInterface;

/**
 * @property mixed|string $site_number
 * @property mixed|UuidInterface $uuid
 * @property mixed $title
 * @property mixed $url
 * @property mixed $feed
 * @property mixed $user
 * @property mixed $last_updated_at
 * @property mixed $site_message
 * @method static findOrFail($webId)
 */
class WebSite extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'title', 'url', 'feed', 'site_message', 'last_updated_at'
    ];

    public function searchableAs(): string
    {
        return 'websites_index';
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deeds(): HasMany
    {
        return $this->hasMany(Deed::class);
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(Subscribe::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'site_message' => $this->site_message,
            'feed' => $this->feed,
        ];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return Carbon::parse($date)->toFormattedDayDateString();
    }

    public function number()
    {
        return $this->site_number;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getFeedUrl()
    {
        return $this->feed;
    }


}
