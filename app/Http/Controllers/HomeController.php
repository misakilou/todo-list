<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        $tasks = $user->tasks->where('parent_id', null);

        $tasks = $tasks->map(function ($item) {
            $subtasks = Task::where('parent_id', $item->id)->get();

            $item->subtasks = $subtasks;

            return $item;
        });


        return view('home', compact('tasks'));
    }
}
