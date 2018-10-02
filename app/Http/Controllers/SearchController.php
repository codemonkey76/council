<?php

namespace App\Http\Controllers;


use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    /**
     * @param Trending $trending
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Trending $trending)
    {
        if (request()->expectsJson()) {
            return Thread::search(request('q'))->paginate(25);
        }

        return view('threads.search', [
            'trending' => $trending->get()
        ]);
    }
}
