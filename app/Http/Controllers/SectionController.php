<?php

namespace App\Http\Controllers;
use DB;
use App\Section;
use App\Template;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Redirect;

class SectionController extends Controller
{
    public function index()
    {
		$sections = Section::all();
		return view('sections.index', compact('sections'));
    }
	
    public function show(Section $section)
    {
		return view('sections.show', compact('section'));
    }

	public function edit(Section $section)
	{
		return view('sections.edit', compact('section'));
	}	
	
	public function create(Section $section)
	{
		return view('sections.create', compact('section'));
	}
	
	public function store()
	{
		$input = Input::all();
		Section::create( $input );
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
