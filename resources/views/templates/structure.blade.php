<!-- /resources/views/template/show.blade.php -->
@extends('layouts.master')

@section('content')

    <h2>{{ $template->template_name }}</h2>
	<h4>{{ $template->template_shortdesc }}</h4>
	<h4>{{ $template->template_longdesc }}</h4>
 
    @if ( !$template->columns->count() || !$template->rows->count() )
        Error: This template has no columns or no rows.
    @else
	
		{!! Form::open(array('action' => 'TemplateController@changestructure', 'id' => 'form')) !!}
		<input name="template_id" type="hidden" value="{{ $template->id }}"/>
		<button style="margin-bottom:15px;" type="submit" class="btn btn-warning">Submit new template structure</button>
	
		<table class="table table-bordered template template-structure" border="1">
	
		<!-- Table header with column names -->
		<thead>
		<tr class="success">

		<td class="header content" style="width: 100px;">Row#</td>
		<td class="header">Row description</td>
		<td style="min-width: 200px;">Styling</td>
	
		@foreach( $template->columns as $column )
			<td style="width: 150px;" class="content header" id="$column->column_num">
				<textarea class="form-control input-sm" rows="6" name="coldesc[{{ $column->column_name }}]" placeholder="{{ $column->column_description }}">{{ $column->column_description }}</textarea>
			</td>
		@endforeach
		</tr>
		
		<!-- Table header with column nums -->

		<tr style="background-color: #EEE;">

		<td></td>
		<td></td>
		<td></td>		

		@foreach( $template->columns as $column )
			<td style="text-align: center; font-weight: bold;">
				<input class="form-control input-sm" type="text" value="{{ $column->column_name }}" name="colnum[{{ $column->column_name }}]" placeholder="{{ $column->column_name }}" style="width: 60px;">
			</td>
		@endforeach

		</tr>
		</thead>
		
		<!-- Table content with row information -->
		<tbody>
		@foreach( $template->rows as $row )
		
			<tr>
			<td style="background-color: #FAFAFA; font-weight: bold;"><input class="form-control input-sm" type="text" value="{{ $row->row_name }}" placeholder="{{ $row->row_name }}" name="rownum[{{ $row->row_name }}]" style="width: 50px;"></td>
			<td style="background-color: #FAFAFA; font-weight: bold;"><input class="form-control input-sm" type="text" placeholder="{{ $row->row_description }}" value="{{ $row->row_description }}" name="rowdesc[{{ $row->row_name }}]"></td>
			
			<td style="background-color: #FAFAFA;">
			<label style="padding-left:0px;" class="checkbox-inline">
				@if ( !empty($row->row_property)) {
					@if ( $row->row_property == "bold") {
					<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="bold" checked> bold
					@else
					<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="bold"> bold
					@endif
				@else
				<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="bold"> bold
				@endif
			</label>
			<label style="padding-left:0px;" class="checkbox-inline">
				@if ( !empty($row->row_property)) {
					@if ( $row->row_property == "tab") {
					<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="tab" checked> tab
					@else
					<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="tab"> tab
					@endif
				@else
				<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="tab"> tab
				@endif
			</label>
			<label style="padding-left:0px;" class="checkbox-inline">
				@if ( !empty($row->row_property)) {
					@if ( $row->row_property == "doubletab") {
					<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="doubletab" checked> doubletab
					@else
					<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="doubletab"> doubletab
					@endif
				@else
				<input class="rowproperty{{ $row->num }}" type="radio" name="row_property[{{ $row->name }}]" id="optionsRadios1" value="doubletab"> doubletab
				@endif
			</label>
			</td>			
			
			<!-- Table cell information, column and row combination -->
			@foreach( $template->columns as $column )
			
				<!-- Create a new variable, column and row combination -->
				{{--*/ $field = 'column' . $column->column_name . '-row' . $row->row_name /*--}}
			
				@if (array_key_exists($field, $disabledFields))
					<td title="{{ $column->column_description }} - {{ $row->row_description }}" class="value" style="background-color: LightGray ! important;" id="{{ $field }}"><input style="display:none;" checked="checked" type="checkbox" name="options[]" value="{{ $field }}" /></td>
				@else
					<td title="{{ $column->column_description }} - {{ $row->row_description }}" class="value" id="{{ $field }}"><input style="display:none;" type="checkbox" name="options[]" value="{{ $field }}" /></td>
				@endif
				
			@endforeach
			</tr>

		@endforeach
		</tbody>
		
		</table>
		
    @endif

	<input type="hidden" name="_token" value="{!! csrf_token() !!}">
	{!! Form::close() !!}	
 
    <p>
        {!! link_to_route('sections.index', 'Back to Sections') !!}
    </p>
	
@endsection

@stop