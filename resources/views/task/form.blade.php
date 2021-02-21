<div class="form-group">
    {{ Form::label('name', __('tm.Name')) }}
    {{ Form::text('name', old('name'), ['class' => ['form-control', $errors->first('name') ? 'is-invalid' : ''], 'id' => 'name']) }}
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="form-group">
    {{ Form::label('description', __('tm.Description')) }}
    {{ Form::textarea('description', old('description'),
        ['class' => 'form-control', 'id' => 'description'],
        ['cols' => 50, 'rows' => 10]) }}
</div>
<div class="form-group">
    {{ Form::label('status_id', __('tm.Status')) }}
    {{ Form::select('status_id', $statuses, old('status_id'),
        ['class' => ['form-control', $errors->first('status_id') ? 'is-invalid' : ''], 'id' => 'status_id', 'placeholder' => '----------']) }}
    @error('status_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="form-group">
    {{ Form::label('assigned_to_id', __('tm.Worker')) }}
    {{ Form::select('assigned_to_id', $workers, old('assigned_to_id'),
        ['class' => 'form-control', 'id' => 'assigned_to_id', 'placeholder' => '----------']) }}
</div>
<div class="form-group">
    {{ Form::label('labels', __('tm.Labels')) }}
    {{ Form::select('labels[]', $labels, old('labels'),
        ['class' => 'form-control', 'id' => 'assigned_to_id', 'multiple', 'placeholder' => '']) }}
</div>
