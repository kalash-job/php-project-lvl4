<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Http\Requests\LabelRequest;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labels = Label::All();
        return view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Label::class);
        $label = new Label();
        return view('label.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabelRequest $request)
    {
        $this->authorize('create', Label::class);
        $user = auth()->user();
        $label = $user->labels()->make($request->all());
        $label->save();
        flash(__('messages.labelWasCreated'), 'success');
        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        $this->authorize('update', $label);
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(LabelRequest $request, Label $label)
    {
        $this->authorize('update', $label);
        $user = auth()->user();
        $label->user()->associate($user);
        $label->fill($request->all());
        $label->save();
        flash(__('messages.labelWasUpdated'), 'success');
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);
        if ($label->tasks->isNotEmpty()) {
            flash(__('messages.labelWasNotDeleted'), 'danger');
        } else {
            $label->delete();
            flash(__('messages.labelWasDeleted'), 'success');
        }
        return redirect()->route('labels.index');
    }
}
