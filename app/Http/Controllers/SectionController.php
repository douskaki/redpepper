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
use Auth;

use Event;
use App\Events\ChangeEvent;

class SectionController extends Controller
{
    public function index(Request $request)
    {
		//only superadmin can see all sections
		if (Gate::denies('superadmin')) {
			if ($request->has('subject_id')) {
				$sections = Section::orderBy('section_name', 'asc')->where('subject_id', $request->input('subject_id'))->where('visible', 'True')->get();
			} else {
				$sections = Section::orderBy('section_name', 'asc')->where('visible', 'True')->get();
			}
		} else {
			if ($request->has('subject_id')) {
				$sections = Section::orderBy('section_name', 'asc')->where('subject_id', $request->input('subject_id'))->get();
			} else {
				$sections = Section::orderBy('section_name', 'asc')->get();
			}
		}
		return view('sections.index', compact('sections'));
    }

    public function manuals()
    {
		//only superadmin can see all sections
		if (Gate::denies('superadmin')) {
			$sections = Section::orderBy('section_name', 'asc')->where('visible', 'True')->get();
		} else {
			$sections = Section::orderBy('section_name', 'asc')->get();
		}
		return view('manuals.index', compact('sections'));
    }

    public function show(Section $section)
    {
		//check if id property exists
		if (!$section->id) {
			abort(403, 'This section no longer exists in the database.');
		}

		//check if visible is set to false and user is a guest
		if (Auth::guest() && $section->visible == "False") {
			abort(403, 'Unauthorized action.');
		}

		//only non guests will see the hidden templates
		if (Auth::guest()) {
			$templates = Template::orderBy('template_name', 'asc')->where('section_id', $section->id)->where('visible', 'True')->get();
		} else {
			$templates = Template::orderBy('template_name', 'asc')->where('section_id', $section->id)->get();
		}
		return view('sections.show', compact('section', 'templates'));
    }

    public function showmanual($id)
    {
		$section = Section::where('id', $id)->first();
		$templates = Template::with('requirements')->where('section_id', $id)->get();

		if (!$section) {
			abort(403, 'This section no longer exists in the database.');
		}

		return view('manuals.show', compact('section', 'templates'));
    }

	public function edit(Section $section)
	{
		//check for superadmin permissions
        if (Gate::denies('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

		//check if id property exists
		if (!$section->id) {
			abort(403, 'This section no longer exists in the database.');
		}

		return view('sections.edit', compact('section'));
	}

	public function create(Section $section)
	{
		//check for superadmin permissions
        if (Gate::denies('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

		return view('sections.create', compact('section'));
	}

	public function store(Request $request)
	{
		//check for superadmin permissions
        if (Gate::denies('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

		//validate input form
		$this->validate($request, [
			'section_name' => 'required|min:4',
			'section_description' => 'required|min:4',
			'subject_id' => 'required'
		]);

		$input = Input::all();
		$section = Section::create($input);

		//log Event
		$event = array(
			"log_event" => "Section",
			"action" => "created",
			"section_id" => $section->id,
			"created_by" => Auth::user()->id
		);

		Event::fire(new ChangeEvent($event));
		return Redirect::route('sections.index')->with('message', 'Section created');
	}

	public function update(Section $section, Request $request)
	{
		//check for superadmin permissions
        if (Gate::denies('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

		//validate input form
		$this->validate($request, [
			'section_name' => 'required|min:4',
			'section_description' => 'required|min:4'
		]);

		$input = array_except(Input::all(), '_method');
		$section->update($input);

		//log Event
		$event = array(
			"log_event" => "Section",
			"action" => "updated",
			"section_id" => $section->id,
			"created_by" => Auth::user()->id
		);

		Event::fire(new ChangeEvent($event));

		return Redirect::route('sections.show', $section->id)->with('message', 'Section updated.');
	}

	public function destroy(Section $section)
	{
		//check for superadmin permissions
        if (Gate::denies('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

		//remove all related templates and content
		$templates = Template::where('section_id', $section->id)->get();

		Template::where('section_id', $section->id)->delete();

		//log Event
		$event = array(
			"log_event" => "Section",
			"action" => "deleted",
			"section_id" => $section->id,
			"created_by" => Auth::user()->id
		);

		Event::fire(new ChangeEvent($event));

		$section->delete();
		return Redirect::route('sections.index')->with('message', 'Section deleted.');
	}
}
