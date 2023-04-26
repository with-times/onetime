<?php

namespace App\Models\Web;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * @property mixed|string $uuid
 * @property mixed $last_modified
 * @property mixed $title
 * @property mixed $description
 * @property mixed $link
 * @property mixed $authors
 */
class Subscribe extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'description', 'link', 'last_modified', 'authors'];

    public function searchableAs(): string
    {
        return 'subscribes_index';
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'web_site_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
            'authors' => $this->authors

        ];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return Carbon::parse($date)->toFormattedDayDateString();
    }
}
