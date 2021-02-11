<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with(['status', 'creator', 'worker'])->get();
        $user = auth()->user();
        return view('task.index', compact('tasks', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Task::class);
        $task = new Task();
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $labels = [];
        return view('task.create', compact('task', 'statuses', 'workers', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);
        $user = auth()->user();
        $task = $user->tasksCreatedByMe()->make($request->all());
        $task->save();
        flash(__('messages.taskWasCreated'), 'success');
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $this->authorize('create', Task::class);
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $labels = [];
        return view('task.edit', compact('task', 'statuses', 'workers', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->fill($request->all());
        $task->save();
        flash(__('messages.taskWasUpdated'), 'success');
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        flash(__('messages.taskWasDeleted'), 'success');
        return redirect()->route('tasks.index');
    }
}
