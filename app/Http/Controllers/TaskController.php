<?php

namespace App\Http\Controllers;

use App\Models\{Label, Status, Task, User};
use App\Http\Requests\TaskRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            ])
            ->with(['status', 'creator', 'worker'])
            ->get();
        $filter = $request->filter;
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $creators = User::getCreatorsForForm();
        return response()->view('task.index', compact('tasks', 'statuses', 'workers', 'creators', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $task = new Task();
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $labels = Label::getLabelsForForm();
        return response()->view('task.create', compact('task', 'statuses', 'workers', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest $request
     * @return RedirectResponse
     */
    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        $task = $user->tasksCreatedByMe()->make($request->except('labels'));
        $task->save();
        if (isset($request->labels[0])) {
            $task->labels()->sync($request->labels);
        }
        flash(__('messages.taskWasCreated'), 'success');
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Response
     */
    public function show(Task $task)
    {
        return response()->view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return Response
     */
    public function edit(Task $task)
    {
        $statuses = Status::getStatusesForForm();
        $workers = User::getWorkersForForm();
        $labels = Label::getLabelsForForm();
        return response()->view('task.edit', compact('task', 'statuses', 'workers', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskRequest $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(TaskRequest $request, Task $task)
    {
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
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task)
    {
        $task->labels()->detach();
        $task->delete();
        flash(__('messages.taskWasDeleted'), 'success');
        return redirect()->route('tasks.index');
    }
}
