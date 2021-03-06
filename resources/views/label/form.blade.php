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
