<!-- /resources/views/users/index.blade.php -->
@extends('layouts.master')

@section('content')
    <h2>Users</h2>
	<h4>Please make a selection of one of the following users</h4>
 
    @if ( !$users->count() )
        No users found in the database!<br><br>
    @else
		<table style="margin-bottom:20px;" class="table section-table dialog table-striped" border="1">

		<tr class="success">
		<td class="header">Username</td>
		<td class="header">First name</td>
		<td class="header">Last name</td>
		<td class="header">E-mail address</td>
		<td class="header">Role</td>
		<td class="header">Department</td>
		<td class="header" style="width: 120px;">Options</td>
		</tr>
		
		@foreach( $users as $users )
		<tr>
		<td>{{ $users->username }}</td>
		<td>{{ $users->firstname }}</td>
		<td>{{ $users->lastname }}</td>
		<td>{{ $users->email }}</td>
		<td>{{ $users->role }}</td>
		<td>{{ $users->department->department_name }}</td>
		{!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('users.destroy', $users->id))) !!}
		<td>
			{!! link_to_route('users.edit', 'Edit', array($users->id), array('class' => 'btn btn-info btn-xs')) !!}
			{!! Form::submit('Delete', array('class' => 'btn btn-danger btn-xs', 'style' => 'margin-left:3px;')) !!}
		</td>
		{!! Form::close() !!}
		</tr>
		@endforeach

		</table>
    @endif
	
@endsection

@stop