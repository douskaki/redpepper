<!-- /resources/views/types/index.blade.php -->
@extends('layouts.master')

@section('content')

	<ul class="breadcrumb breadcrumb-section">
	<li><a href="{!! url('/'); !!}">Home</a></li>
	<li class="active">Types</li>
	</ul>

	<h2>Types</h2>
	<h4>Please make a selection of one of the following types</h4>

	@if ( !$types->count() )
		No types found in the database!<br><br>
	@else
		<table class="table section-table dialog table-striped" border="1">

		<tr class="success">
		<td class="header">Name</td>
		<td class="header">Description</td>
		<td class="header" style="width: 120px;">Options</td>
		</tr>

		@foreach( $types as $type )
			<tr>
			<td><a href="{!! url('types'); !!}/{{ $type->id }}">{{ $type->type_name }}</a></td>
			<td>{{ $type->type_description }}</td>
			{!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('types.destroy', $type->id), 'onsubmit' => 'return confirm(\'Are you sure to delete this type?\')')) !!}
			<td>
			{!! link_to_route('types.edit', 'Edit', array($type->id), array('class' => 'btn btn-info btn-xs')) !!}
			{!! Form::submit('Delete', array('class' => 'btn btn-danger btn-xs', 'style' => 'margin-left:3px;')) !!}
			</td>
			{!! Form::close() !!}
			</tr>
		@endforeach

		</table>
	@endif

	<p>
	{!! link_to_route('types.create', 'Create Type') !!}
	</p>

@endsection
