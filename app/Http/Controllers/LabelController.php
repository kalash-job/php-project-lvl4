<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Http\Requests\LabelRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

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
     * @return Response
     */
    public function index()
    {
        $labels = Label::all();
        return response()->view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $label = new Label();
        return response()->view('label.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LabelRequest  $request
     * @return RedirectResponse
     */
    public function store(LabelRequest $request)
    {
        $user = auth()->user();
        $user->labels()->create($request->all());
        flash(__('messages.labelWasCreated'), 'success');
        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Label  $label
     * @return Response
     */
    public function edit(Label $label)
    {
        return response()->view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LabelRequest  $request
     * @param  Label  $label
     * @return RedirectResponse
     */
    public function update(LabelRequest $request, Label $label)
    {
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
     * @param  Label  $label
     * @return RedirectResponse
     */
    public function destroy(Label $label)
    {
        if ($label->tasks->isNotEmpty()) {
            flash(__('messages.labelWasNotDeleted'), 'danger');
        } else {
            $label->delete();
            flash(__('messages.labelWasDeleted'), 'success');
        }
        return redirect()->route('labels.index');
    }
}
