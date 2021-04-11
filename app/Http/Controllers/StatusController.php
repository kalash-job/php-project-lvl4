<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StatusRequest;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::all();
        return response()->view('status.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Status::class);
        $status = new Status();
        return response()->view('status.create', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StatusRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StatusRequest $request)
    {
        $this->authorize('create', Status::class);
        $user = auth()->user();
        $status = $user->statuses()->make($request->all());
        $status->save();
        flash(__('messages.statusWasCreated'), 'success');
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Status  $task_status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $task_status)
    {
        $this->authorize('update', $task_status);
        $status = $task_status;
        return response()->view('status.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StatusRequest  $request
     * @param  \App\Models\Status  $task_status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StatusRequest $request, Status $task_status)
    {
        $this->authorize('update', $task_status);
        if ($request->user()->cannot('update', $task_status)) {
            abort(403);
        }
        $task_status->user()->associate(auth()->user());
        $task_status->fill($request->all());
        $task_status->save();
        flash(__('messages.statusWasUpdated'), 'success');
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Status  $task_status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Status $task_status)
    {
        $this->authorize('delete', $task_status);
        $user = auth()->user();
        if ($user->cannot('delete', $task_status)) {
            flash(__('messages.statusWasNotDeleted'), 'danger');
        } elseif ($task_status->tasks->isNotEmpty()) {
            flash(__('messages.statusWasNotDeleted'), 'danger');
        } else {
            $task_status->delete();
            flash(__('messages.statusWasDeleted'), 'success');
        }
        return redirect()->route('task_statuses.index');
    }
}
