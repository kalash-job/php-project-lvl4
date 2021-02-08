<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::All();
        $isAuth = Auth::check();
        return view('status.index', compact('statuses', 'isAuth'));
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
        return view('status.create', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $task_status)
    {
        $this->authorize('create', Status::class);
        $status = $task_status;
        return view('status.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $task_status)
    {
        if ($request->user()->cannot('update', $task_status)) {
            abort(403);
        }
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
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $task_status)
    {
        $user = auth()->user();
        if ($user->cannot('delete', $task_status)) {
            flash(__('messages.statusWasNotDeleted'), 'danger');
        } else {
            $task_status->delete();
            flash(__('messages.statusWasDeleted'), 'success');
        }
        return redirect()->route('task_statuses.index');
    }
}
