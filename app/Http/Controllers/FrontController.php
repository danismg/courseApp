<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeTransactionRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubscribeTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        $courses = Course::with(['teacher', 'category', 'students'])->orderByDesc('id')->get();
        return view('front.index', compact('courses'));
    }

    public function details(Course $course)
    {
        return view('front.details', compact('course'));
    }

    public function pricing()
    {
        return view('front.pricing');
    }

    // checkout
    public function checkout()
    {
        return view('front.checkout');
    }

    // category
    public function category(Category $category)
    {
        $courses = Course::with(['teacher', 'category', 'students'])
            ->where('category_id', $category->id)
            ->orderByDesc('id')
            ->get();
        return view('front.category', compact('category', 'courses'));
    }

    // chectout store
    public function checkout_store(StoreSubscribeTransactionRequest $request)
    {
        $user = Auth::user();
        // Memeriksa apakah pengguna memiliki langganan aktif
        if (!$user->hasActiveSubscription()) {
            // Jika tidak, arahkan pengguna ke halaman harga
            return redirect()->route('front.pricing');
        }

        DB::transaction(function () use ($request, $user) {
            // validate -> request StoreCategoryRequest
            $validated = $request->validated();
            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }

            $validated['user_id'] = $user->id;
            $validated['total_amount'] = 429000;
            $validated['is_paid'] = false;

            $transaction = SubscribeTransaction::create($validated);
            // web design => web-design
        });

        return redirect()->route('dashboard');
    }

    public function learning(Course $course, $courseVideo)
    {
        $user = Auth::user();


        // Memeriksa apakah pengguna memiliki langganan aktif
        if (!$user->hasActiveSubscription()) {
            // Jika tidak, arahkan pengguna ke halaman harga
            return redirect()->route('front.pricing');
        }

        // Mengambil video pertama dari course yang sesuai dengan ID $courseVideo
        $video = $course->course_videos->firstWhere('id', $courseVideo);

        // Menyinkronkan pengguna dengan kursus tanpa melepaskan hubungan yang sudah ada
        $user->courses()->syncWithoutDetaching($course->id);

        return view('front.learning', compact('course', 'video'));
    }
}
