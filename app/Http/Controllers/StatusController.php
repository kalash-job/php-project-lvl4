<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Requests\StatusRequest;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class StatusController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Status::class, 'task_status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statuses = Status::all();
        return response()->view('status.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $status = new Status();
        return response()->view('status.create', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StatusRequest  $request
     * @return RedirectResponse
     */
    public function store(StatusRequest $request)
    {
        $user = auth()->user();
        $status = $user->statuses()->make($request->all());
        $status->save();
        flash(__('messages.statusWasCreated'), 'success');
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Status  $task_status
     * @return Response
     */
    public function edit(Status $task_status)
    {
        $status = $task_status;
        return response()->view('status.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StatusRequest  $request
     * @param  Status  $task_status
     * @return RedirectResponse
     */
    public function update(StatusRequest $request, Status $task_status)
    {
        $user = auth()->user();
        $task_status->user()->associate($user);
        $task_status->fill($request->all());
        $task_status->save();
        flash(__('messages.statusWasUpdated'), 'success');
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Status  $task_status
     * @return RedirectResponse
     */
    public function destroy(Status $task_status)
    {
        if ($task_status->tasks()->exists()) {
            flash(__('messages.statusWasNotDeleted'), 'danger');
        } else {
            $task_status->delete();
            flash(__('messages.statusWasDeleted'), 'success');
        }
        return back();
    }
}
