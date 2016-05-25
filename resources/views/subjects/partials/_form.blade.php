<!-- /resources/views/subjects/partials/_form.blade.php -->
<div class="form-horizontal">

	<div class="form-group">
		{!! Form::label('subject_name', 'Subject name:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-6">
		{!! Form::text('subject_name', null, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('subject_description', 'Subject description:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-6">
		{!! Form::textarea('subject_description', null, ['class' => 'form-control', 'rows' => '4']) !!}
		</div>
	</div>
	
	<div class="form-group">
		{!! Form::label('subject_longdesc', 'Subject longdesc:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-6">
		{!! Form::textarea('subject_longdesc', null, ['id' => 'subject_longdesc', 'class' => 'form-control', 'rows' => '7']) !!}
		</div>
	</div>
	
	<div class="form-group">
		{!! Form::label('visible', 'Visible:', array('class' => 'col-sm-3 control-label')) !!}
		<div class="col-sm-6">
		{!! Form::select('visible', ['True' => 'True', 'False' => 'False'], $subject->visible, ['id' => 'visible', 'class' => 'form-control']) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::submit($submit_text, ['class' => 'btn btn-primary']) !!}
	</div>

</div>
