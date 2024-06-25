<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'path_video', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // check apakah di berlangganan atau tidak
    public function subscribe_transactions()
    {
        return $this->hasMany(SubscribeTransaction::class);
    }

    public function hasActiveSubscription()
    {
        $latestSubscription = $this->subscribe_transactions()->where('is_paid', true)->latest()->first();

        if (!$latestSubscription) {
            return false;
        }

        // Penjelasan :
        // 1. Mengambil tanggal berlangganan terakhir
        // 2. Menambahkan 1 bulan ke tanggal berlangganan terakhir
        // 3. Mengambil tanggal sekarang
        // 4. Membandingkan apakah tanggal sekarang lebih kecil dari tanggal berlangganan terakhir + 1 bulan
        $subcriptionEndDate = Carbon::parse($latestSubscription->subscription_start_date)->addMonth();
        return Carbon::now()->lessThanOrEqualTo($subcriptionEndDate);
    }
}
