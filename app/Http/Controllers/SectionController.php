<?php

namespace App\Http\Controllers;
use DB;
use App\Section;
use App\Template;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Redirect;

use Gate;
use App\User;

class SectionController extends Controller
{
    public function index(Request $request)
    {
	
		if ($request->input('group') == "corep") {
			$sections = Section::orderBy('section_name', 'asc')->where('subject_id', 1)->get();
		} elseif ($request->input('group') == "finrep") {
			$sections = Section::orderBy('section_name', 'asc')->where('subject_id', 2)->get();
		} elseif ($request->input('group') == "liquidity") {
			$sections = Section::orderBy('section_name', 'asc')->where('subject_id', 3)->get();
		} elseif ($request->input('group') == "other") {
			$sections = Section::orderBy('section_name', 'asc')->where('subject_id', 4)->get();
		} else {
			$sections = Section::orderBy('section_name', 'asc')->get();
		}
		return view('sections.index', compact('sections'));
    }
	
    public function show(Section $section)
    {
		$templates = Template::orderBy('template_name', 'asc')->where('section_id', $section->id)->get();
		return view('sections.show', compact('section', 'templates'));
    }

	public function edit(Section $section)
	{
		//check for superadmin permissions
        if (Gate::denies('superadmin')) {
            abort(403, 'Unauthorized action. Only superadmin users are allowed to edit sections.');
        }
	
		return view('sections.edit', compact('section'));
	}	
	
	public function create(Section $section)
	{
		return view('sections.create', compact('section'));
	}
	
	public function store()
	{
		$input = Input::all();
		Section::create($input);
		return Redirect::route('sections.index')->with('message', 'Section created');
	}
	 
	public function update(Section $section)
	{
		$input = array_except(Input::all(), '_method');
		$section->update($input);
		return Redirect::route('sections.show', $section->id)->with('message', 'Section updated.');
	}
	 
	public function destroy(Section $section)
	{
		$section->delete();
		return Redirect::route('sections.index')->with('message', 'Section deleted.');
	}
}
