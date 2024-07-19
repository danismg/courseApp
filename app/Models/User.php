<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'occupation',
        'avatar',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    // check apakah di berlangganan atau tidak
    public function subscribe_transactions()
    {
        return $this->hasMany(SubscribeTransaction::class);
    }

    public function hasActiveSubscription()
    {
        $latestSubscription = $this->subscribe_transactions()
            ->where('is_paid', true)
            ->latest('updated_at')
            ->first();

        if (!$latestSubscription) {
            return false;
        }

        // Penjelasan :
        // 1. Mengambil tanggal berlangganan terakhir
        // 2. Menambahkan 1 bulan ke tanggal berlangganan terakhir
        $subcriptionEndDate = Carbon::parse($latestSubscription->subscription_start_date)->addMonth(1);
        // 3. Mengambil tanggal sekarang
        // 4. Membandingkan apakah tanggal sekarang lebih kecil dari tanggal berlangganan terakhir + 1 bulan
        return Carbon::now()->lessThanOrEqualTo($subcriptionEndDate);
    }
}
