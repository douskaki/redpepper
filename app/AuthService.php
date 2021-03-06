<?php

namespace App;
use App\User;
use App\Section;
use App\Subject;
use Auth;
use Gate;

class AuthService {

	public function getSubjectsList()
	{
		//this function is needed to return all the subjects where the user has rights on.
		$subjectRights = array();

		$subjects = Subject::orderBy('subject_name', 'asc')->get();

		foreach ($subjects as $subject) {
			if (Auth::user()->can('update-subject', $subject)) {
				array_push($subjectRights,$subject->id);
			}
		}

		//abort if sectionRights array is empty
		if (empty($subjectRights)) {
			abort(403, 'Unauthorized action. You don\'t have access to any sections.');
		}

		//return Array with sections
		return $subjectRights;
	}

	public function getSectionsList()
	{
		//this function is needed to return all the sections where the user has rights on. Auth::user()->sections, will only return the sections that have been added manually.
		$sectionRights = array();

		$sections = Section::orderBy('section_name', 'asc')->get();

		foreach ($sections as $section) {
			if (Auth::user()->can('update-section', $section)) {
				array_push($sectionRights,$section->id);
			}
		}

		//abort if sectionRights array is empty
		if (empty($sectionRights)) {
			abort(403, 'Unauthorized action. You don\'t have access to any sections.');
		}

		//return Array with sections
		return $sectionRights;
	}

	public function getTemplatesList() {

		if (Auth::check()) {

			$userrights = UserRights::where('username_id', Auth::user()->id)->get();

			$templatesRights = array();
			$userrights = $userrights->toArray();
			if (!empty($userrights)) {
				foreach ($userrights as $userright) {
					$templates = Template::where('section_id', $userright['section_id'])->get();
					if (!empty($templates)) {
						foreach ($templates as $template) {
							array_push($templatesRights,$template->id);
						}
					}
				}
			}
			return $templatesRights;
		} else {
			return null;
		}
	}

}

?>
