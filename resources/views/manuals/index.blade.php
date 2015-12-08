<!-- /resources/views/manuals/index.blade.php -->
@extends('layouts.master')

@section('content')
    <h2>Manuals</h2>
	<h4>The manual section facilitates in order to completely understand the regulatory reporting process and templates. The manuals contain legal references, guidance and interpretations by users. On a cell level the manuals could provide mapping information and validations. For one of the manuals, please make a selection below:</h4>
 
    @if ( !$sections->count() )
        No sections found in the database!<br><br>
    @else
		<table style="margin-bottom:20px;" class="table section-table dialog table-striped" border="1">

		<tr class="success">
		<td style="width:40%;" class="header">Name</td>
		<td class="header">Description</td>
		</tr>
		
		@foreach( $sections as $section )
		<tr>
		<td><a href="{{ url('manuals/' . $section->id) }}">{{ $section->section_name }}</a></td>
		<td>{{ $section->section_description }}</td>
		</tr>
		@endforeach

		</table>
    @endif
	
@endsection

@stop