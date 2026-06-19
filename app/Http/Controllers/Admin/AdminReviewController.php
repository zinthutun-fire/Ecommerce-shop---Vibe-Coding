<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;

class AdminReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index()
    {
        $reviews = Review::with('user', 'product')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $this->reviewService->approve($review);
        return back()->with('success', 'Review approved!');
    }

    public function reject(Review $review)
    {
        $this->reviewService->reject($review);
        return back()->with('success', 'Review rejected!');
    }
}
