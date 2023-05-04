<?php

namespace App\Models;

use App\Models\Web\Deed;
use App\Models\Web\Subscribe;
use App\Models\Web\WebSite;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property mixed $email
 * @property mixed|string $password
 * @method static create($data)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     *      The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute(): string
    {
        $data = $this->email;
        $reg = '/^([0-9]{5,11})@qq.com$/';
        if (preg_match($reg, $data)) {
            return 'https://q1.qlogo.cn/g?b=qq&nk='.str_replace('@qq.com', '', $data).'&s=100';
        } else {
            return 'https://seccdn.libravatar.org/avatar/'.md5($data).'?d=retro';
        }
    }
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['avatar'] = $this->getAvatarAttribute();
        return $data;
    }

    public function websites(): HasMany
    {
        return $this->hasMany(WebSite::class);
    }

    /**
     * Get the deeds for the user.
     */
    public function deeds(): HasMany
    {
        return $this->hasMany(Deed::class);
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(Subscribe::class);
    }
}
