<!-- /resources/views/users/editrights.blade.php -->
@extends('layouts.master')

@section('content')

	<div class="form-horizontal">

	<h2>Edit User "{{ $user->username }}"</h2>

	@if (count($errors) > 0)
		<div class="alert alert-danger">
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
		</div>
	@endif

	{!! Form::open(array('action' => 'UserController@updaterights', 'id' => 'form')) !!}
	<input name="username_id" type="hidden" value="{{ $user->id }}"/>
	<div class="form-group">
	{!! Form::label('role', 'Role:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
	<select name="role" class="form-control" class="form-control" id="role">
	@foreach( $roles as $role )
		@if ( $user->role == $role) {
			<option selected="selected">{{ $role }}</option>
		@else
			<option>{{ $role }}</option>
		@endif
	@endforeach
	</select>
	</div>
	</div>

	@if ( $sections->count() )
		<div class="form-group">
		<table class="table table-striped table-condensed">
		<tr class="success">
		<th><h4>Section</h4></th>
		<th style="text-align: center;"><h4>Selected rights</h4></th>
		</tr>
		<tr class="allrights notvisible">
		<td><strong>All</strong></td>
		<td style="text-align: center;"><input type="checkbox"></td>
		</tr>
		@foreach( $sections as $section )
			<tr>
			<td><strong>{{ $section->section_name }}</strong></td>
			@if ( in_array($section->id, $sectionrights) )
				<td class="rights" style="text-align: center;"><input name="section[{{ $section->id }}]" id="section_rights" checked type="checkbox" value="{{ $section->id }}"></td>
			@else
				<td class="rights" style="text-align: center;"><input name="section[{{ $section->id }}]" id="section_rights" type="checkbox" value="{{ $section->id }}"></td>
			@endif
			</tr>
		@endforeach
		</table>
		</div>
	@endif

	<button type="submit" class="btn btn-warning">Submit rights</button>
	{!! Form::close() !!}

	</div>

@endsection