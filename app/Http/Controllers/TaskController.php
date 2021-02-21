<?php

namespace App\Http\Controllers;

use App\Models\{Label, Status, Task, User};
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            ])
            ->with(['status', 'creator', 'worker'])
            ->get();
        $filter = Request('filter');
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $creators = User::getCreatorsForForm();
        return view('task.index', compact('tasks', 'statuses', 'workers', 'creators', 'filter'));
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
        $labels = Label::getLabelsForForm();
        return view('task.create', compact('task', 'statuses', 'workers', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);
        $user = auth()->user();
        $task = $user->tasksCreatedByMe()->make($request->all());
        $task->save();
        $task->labels()->sync($request->labels);
        flash(__('messages.taskWasCreated'), 'success');
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $labels = Label::getLabelsForForm();
        return view('task.edit', compact('task', 'statuses', 'workers', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->fill($request->all());
        $task->save();
        $labels = isset($request->labels[0]) ? $request->labels : [];
        $task->labels()->sync($labels);
        flash(__('messages.taskWasUpdated'), 'success');
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
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
