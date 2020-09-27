<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Liste les tasks / sous tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', $request->bearerToken())->first();

        $tasks = $user->tasks->where('parent_id', null);

        $tasks = $tasks->map(function ($item) {
            $item->subtasks = Task::where('parent_id', $item->id)->get();

            return $item;
        });

        return response()->json(['tasks' => $tasks], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Creer une nouvelle task / sous task
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|between:1,255',
            'parent' => 'integer|nullable'
        ]);

        $user = User::where('api_token', $request->bearerToken())->first();

        if (!$user) {
            return response()->json(['errors' => ['Forbidden']], 401);
        }

        $task = Task::create([
            'title' => $request->title,
            'parent_id' => $request->parent,
            'user_id' => $user->id
            ]);



        return response()->json(['task' => $task, 'userName' => $user->name], 201);
    }


    /**
     * Complete une task / sous task
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAsDone($id)
    {
        $task = Task::findOrFail($id);

        $done = !$task->done;
        $task->update([
            'done' => $done,
            ]);

        return response()->json("OK", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update une task / sous task
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|between:1,255',
            'parent' => 'integer|nullable'
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'title' => $request->title,
            ]);

        return response()->json(['task' => $task], 200);
    }

    /**
     * Supprime une task
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if (!$task->parent_id) {
            Task::where('parent_id', $task->id)->delete();
        }

        $task->delete();

        return response()->json('OK', 200);
    }
}
