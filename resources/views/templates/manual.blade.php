<!-- /resources/views/manuals/show.blade.php -->
@extends('layouts.master')

@section('content')

	<ul class="breadcrumb breadcrumb-section">
	  <li><a href="{!! url('/'); !!}">Home</a></li>
	  @if ($template->section->subject->parent)
		  <li><a href="{{ route('subjects.show', $template->section->subject->parent) }}">{{ $template->section->subject->parent->subject_name }}</a></li>
	  @endif
	  <li><a href="{{ route('subjects.show', $template->section->subject) }}">{{ $template->section->subject->subject_name }}</a></li>
	  <li><a href="{{ route('subjects.sections.show', array($template->section->subject, $template->section)) }}">{{ $template->section->section_name }}</a></li>
	  <li><a href="{{ route('subjects.sections.templates.show', array($template->section->subject, $template->section, $template)) }}">{{ $template->template_name }}</a></li>
	  <li class="active">Manual</li>
	</ul>

	<h4>{{ $template->template_name }}</h4>
	<h5 class="tinymce">{!! Format::contentAdjust($template->template_shortdesc) !!}</h5>
	<h5 class="tinymce">{!! Format::contentAdjust($template->section_shortdesc) !!}</h5>
	<h5>{!! Format::contentAdjust($template->template_longdesc) !!}</h5>

	@if ( $template->requirements->count() )

		<h5><strong>Template content</strong></h5>

		<table class="table table-bordered book" border="0">
		<tr class="success">
		<td>Row code</td>
		<td>Column code</td>
		<td>Content type</td>
		<td>Requirement</td>
		</tr>
		@foreach( $template->requirements as $requirement )
			@if ( $requirement->content_type != "disabled" )
				<tr>
				<td>{{ $requirement->row_code }}</td>
				<td>{{ $requirement->column_code }}</td>
				<td>{{ $requirement->content_type }}</td>
				<td>{{ $requirement->content }}</td>
				</tr>
			@endif
		@endforeach
		</table>
	@endif

	<!-- technical content -->
	@if ( $technical->count() )

		<h5><strong>Technical template content</strong></h5>

		<!-- technical content -->
		<table class="table table-bordered book" border="0">
			<tr class="success">
				<th class="source">Row code</th>
				<th class="source">Column code</th>
				<th class="source">System</th>
				<th class="type">Type</th>
				<th class="content">Value</th>
				<th class="description">Description</th>
			</tr>

			@foreach( $technical as $row )
				@if (is_object($row->source) && is_object($row->type))
					<tr id="{{ $row->id }}">
						<td class="row_code">{{ $row->row_code }}</td>
						<td class="column_code">{{ $row->column_code }}</td>
						<td class="source">{{ $row->source->source_name }}</td>
						<td class="type">{{ $row->type->type_name }}</td>
						<td class="content">{{ $row->content }}</td>

						@if ( $row->type->descriptions->count() )
							@foreach( $row->type->descriptions as $description )
								@if ($description->content == $row->content)
									{{--*/ $description_type = $description->description; /*--}}
								@endif
							@endforeach
						@else
							{{--*/ $description_type = $row->description; /*--}}
						@endif

						@if ( empty($description_type) )
							{{--*/ $description_type = $row->description; /*--}}
						@endif

						<td class="description">{{ $description_type }}</td>
					</tr>
				@endif
			@endforeach

		</table>
	@endif

@endsection
