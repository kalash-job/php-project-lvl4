<?php

namespace App\Http\Controllers;

use App\Models\{Label, User};
use App\Http\Requests\LabelRequest;

class LabelController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Label::class, 'label');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labels = Label::all();
        return response()->view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$this->authorize('create', Label::class);
        $label = new Label();
        return response()->view('label.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LabelRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LabelRequest $request)
    {
        //$this->authorize('create', Label::class);
        $user = auth()->user();
        $user->labels()->create($request->all());
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
        //$this->authorize('update', $label);
        return response()->view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LabelRequest  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LabelRequest $request, Label $label)
    {
        //$this->authorize('update', $label);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Label $label)
    {
        //$this->authorize('delete', $label);
        if ($label->tasks->isNotEmpty()) {
            flash(__('messages.labelWasNotDeleted'), 'danger');
        } else {
            $label->delete();
            flash(__('messages.labelWasDeleted'), 'success');
        }
        return redirect()->route('labels.index');
    }
}
