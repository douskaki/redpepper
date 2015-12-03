<?php

namespace App\Http\Controllers;
use DB;
use App\Section;
use App\Template;
use App\TemplateRow;
use App\TemplateColumn;
use App\TemplateField;
use App\Requirement;
use App\Technical;
use App\TechnicalType;
use App\TechnicalSource;
use App\ChangeRequest;
use App\DraftField;
use App\DraftRequirement;
use App\DraftTechnical;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Validator;
use Session;

class TemplateController extends Controller
{
	//function to show template
    public function show(Section $section, Template $template, Request $request)
    {
		//set empty search value
		$searchvalue = 'empty';
		
		//return field_id, e.g. R-010 as row010 or column010
		if ($request->has('field_id')) {
			//replace R- or C- with row or column
			if (preg_match('/R-/', $request->input('field_id'))) {
				$searchvalue = str_ireplace("R-", "row", $request->input('field_id'));
			}
			
			if (preg_match('/C-/', $request->input('field_id'))) {
				$searchvalue = str_ireplace("C-", "column", $request->input('field_id'));
			}
		}
		
		//if both row and column are set, return combination, else only row or column
		if ($request->has('row') && $request->has('column')) {
			$searchvalue = "column" . $request->input('column') . "-row" . $request->input('row');
		} else if ($request->has('row')) {
			$searchvalue = "row" . $request->input('row');
		} else if ($request->has('column')) {
			$searchvalue = "column" . $request->input('column');
		}
		
		$disabledFields = $this->getDisabledFields($template);
		return view('templates.show', compact('section', 'template', 'disabledFields', 'searchvalue'));
    }
	
	//function to disabled fields
	public function getDisabledFields(Template $template)
	{
		$disabledFields = TemplateField::where('template_id', $template->id)->where('property', 'disabled')->get();
		
		//Create new arrays to restructure result
		$arraydisabled=array();
		//Restructure array
		if (!empty($disabledFields)) {
			foreach ($disabledFields as $disabledField) {
				$rowname = $disabledField->row_name;
				$columnname = $disabledField->column_name;
				$field = 'column' . trim($columnname) . '-' . 'row' . trim($rowname);
				$arraydisabled[$field] = $disabledField->property;
			}
		}
		
		return $arraydisabled;
	}		
	
	//function to edit template
	public function edit(Section $section, Template $template)
	{
		$sections = Section::orderBy('section_name', 'asc')->get();
		return view('templates.edit', compact('sections', 'section', 'template'));
	}
	
	//function to structure template
	public function changestructure(Request $request)
	{
		if ($request->isMethod('post')) {
			
			if ($request->has('template_id')) {

				//update column numbers
				if ($request->has('colnum')) {
					foreach($request->input('colnum') as $key => $value) {
						if (!empty($value)) {
							TemplateColumn::where('template_id', $request->input('template_id'))->where('column_name', $key)->update(['column_name' => $value]);
							Requirement::where('template_id', $request->input('template_id'))->where('field_id', 'C-' . $key)->update(['field_id' => 'C-' . $value]);
						}
					}
				}
			
				//update column desc
				if ($request->has('coldesc')) {
					foreach($request->input('coldesc') as $key => $value) {
						if (!empty($value)) {
							TemplateColumn::where('template_id', $request->input('template_id'))->where('column_name', $key)->update(['column_description' => $value]);
						}
					}
				}
				
				//update row numbers
				if ($request->has('rownum')) {
					foreach($request->input('rownum') as $key => $value) {
						if (!empty($value)) {
							TemplateRow::where('template_id', $request->input('template_id'))->where('row_name', $key)->update(['row_name' => $value]);
							Requirement::where('template_id', $request->input('template_id'))->where('field_id', 'R-' . $key)->update(['field_id' => 'R-' . $value]);
						}
					}
				}

				//update row desc
				if ($request->has('rowdesc')) {
					foreach($request->input('rowdesc') as $key => $value) {
						if (!empty($value)) {
							TemplateRow::where('template_id', $request->input('template_id'))->where('row_name', $key)->update(['row_description' => $value]);
						}
					}
				}

				//delete disabled cells
				TemplateField::where('template_id', $request->input('template_id'))->where('property', 'disabled')->delete();
				if ($request->has('options')) {
					foreach($request->input('options') as $disabled){
						//split options into row and column
						list($before, $after) = explode('-row', $disabled, 2);
						$column = str_ireplace("column", "", "$before");
						$row = $after;
						//create new disabled field
						$TemplateField = new TemplateField;
						$TemplateField->template_id = $request->input('template_id');
						$TemplateField->row_name = $row;
						$TemplateField->column_name = $column;
						$TemplateField->property = 'disabled';
						$TemplateField->save();
					}
				}
				
				//update changerequests, technical and fields properties
				if ($request->has('colnum')) {
					foreach($request->input('colnum') as $columnkey => $columnvalue) {
						if (!empty($columnvalue)) {
							if ($request->has('rownum')) {
								foreach($request->input('rownum') as $rowkey => $rowvalue) {
									if (!empty($rowvalue)) {
										TemplateField::where('template_id', $request->input('template_id'))->where('row_name', $rowkey)->where('column_name', $columnkey)->where('property', '!=' , 'disabled')->update(['row_name' => $rowvalue, 'column_name' => $columnvalue]);
										Technical::where('template_id', $request->input('template_id'))->where('row_num', $rowkey)->where('col_num', $columnkey)->update(['row_num' => $rowvalue, 'col_num' => $columnvalue]);
										ChangeRequest::where('template_id', $request->input('template_id'))->where('row_number', $rowkey)->where('column_number', $columnkey)->update(['row_number' => $rowvalue, 'column_number' => $columnvalue]);
									}
								}
							}
						}
					}
				}
				
			}
		}
		
		return Redirect::route('sections.index')->with('message', 'Template structure updated.');
	}
	
	//function to structure template
	public function structure($id)
	{
		$template = Template::find($id);
		$disabledFields = $this->getDisabledFields($template);
		return view('templates.structure', compact('section', 'template', 'disabledFields'));
	}	
	
	//function to add new template
	public function store(Section $section)
	{
		$input = Input::all();
		$input['section_id'] = $section->id;
		Template::create( $input );
		return Redirect::route('sections.show', $section->id)->with('message', 'Template created.');
	}
	
	//function to update template
	public function update(Request $request)
	{
		$input = array_except(Input::all(), '_method');
		$template->update($input);
		return Redirect::route('sections.templates.show', [$section->id, $template->id])->with('message', 'Template updated.');
	}
	
	//function to delete template
	public function destroy(Section $section, Template $template)
	{
		$template->delete();
		return Redirect::route('sections.show', $section->id)->with('message', 'Template deleted.');
	}
	
	//content for the pop-up
    public function getCellContent()
    {
		//abort if template_id and cell_id are not set
		if (empty($_GET['template_id']) || empty($_GET['cell_id'])) {
			abort(404, 'Content cannot be found with invalid arguments.');
		}
		
		//split input into row and column
		list($before, $after) = explode('-row', $_GET['cell_id'], 2);
		$columnnum = str_ireplace("column", "", "$before");
		$rownum = $after;				
		
		return view('templates.cell', [
			'template' => Template::find($_GET['template_id']),
			'row' => TemplateRow::where('template_id', $_GET['template_id'])->where('row_name', $rownum)->first(),
			'column' => TemplateColumn::where('template_id', $_GET['template_id'])->where('column_name', $columnnum)->first(),
			'regulation_row' => Requirement::where('template_id', $_GET['template_id'])->where('field_id', 'R-' . $rownum)->where('content_type', 'regulation')->first(),
			'regulation_column' => Requirement::where('template_id', $_GET['template_id'])->where('field_id', 'C-' . $columnnum)->where('content_type', 'regulation')->first(),
			'interpretation_row' => Requirement::where('template_id', $_GET['template_id'])->where('field_id', 'R-' . $rownum)->where('content_type', 'interpretation')->first(),
			'interpretation_column' => Requirement::where('template_id', $_GET['template_id'])->where('field_id', 'C-' . $columnnum)->where('content_type', 'interpretation')->first(),
			'technical' => Technical::where('template_id', $_GET['template_id'])->where('row_num', $rownum)->where('col_num', $columnnum)->get(),
			'field_regulation' => TemplateField::where('template_id', $_GET['template_id'])->where('row_name', $rownum)->where('column_name', $columnnum)->where('property', 'regulation')->get(),
			'field_interpretation' => TemplateField::where('template_id', $_GET['template_id'])->where('row_name', $rownum)->where('column_name', $columnnum)->where('property', 'interpretation')->get(),
			'field_property1' => TemplateField::where('template_id', $_GET['template_id'])->where('row_name', $rownum)->where('column_name', $columnnum)->where('property', 'property1')->get(),
			'field_property2' => TemplateField::where('template_id', $_GET['template_id'])->where('row_name', $rownum)->where('column_name', $columnnum)->where('property', 'property2')->get()
		]);
		
    }
	
}
