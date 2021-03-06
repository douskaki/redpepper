<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\UserRights;
use App\Section;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
	 * @return void
	 */
	public function boot(GateContract $gate)
	{
		$this->registerPolicies($gate);

		$gate->define('update-subject', function ($user, $subject) {
			if ($user->role === "superadmin") {
				return true;
			}
			if ($user->role === "builder" || $user->role === "admin") {
				//check if the subject belongs where the user has rights on
				if ($user->subjects->contains($subject)) {
					return true;
				}
			}
		});

		$gate->define('update-section', function ($user, $section) {
			if ($user->role === "superadmin") {
				return true;
			}
			if ($user->role === "builder" || $user->role === "admin") {
				//check if the section belongs where the user has rights on
				if ($user->sections->contains($section)) {
					return true;
				}

				//check if the user has rights on the subject_id of the section
				$userrights = UserRights::where('username_id', $user->id)->where('subject_id', $section->subject_id)->get();
				if ($userrights->count()) {
					return true;
				}
			}
		});

		$gate->define('create-changerequest', function ($user, $template) {
			if ($user->role === "superadmin") {
				return true;
			}
			if ($user->role === "builder" || $user->role === "admin" || $user->role === "contributor") {
				$userrights = UserRights::where('username_id', $user->id)->where('section_id', $template->section_id)->get();
				if ($userrights->count()) {
					return true;
				}
			}
		});

		$gate->define('approve-changerequest', function ($user, $template) {
			if ($user->role === "superadmin") {
				return true;
			}
			if ($user->role === "builder" || $user->role === "admin" || $user->role === "reviewer") {
				$userrights = UserRights::where('username_id', $user->id)->where('section_id', $template->section_id)->get();
				if ($userrights->count()) {
					return true;
				}
			}
		});

		//added to show hidden content
		$gate->define('see-nonvisible-content', function ($user) {
			if ($user->role === "superadmin" || $user->role === "builder" || $user->role === "admin" || $user->role === "contributor" || $user->role === "reviewer") {
				return true;
			}
		});

		$gate->define('superadmin', function ($user) {
			return $user->role === "superadmin";
		});

		$gate->define('admin', function ($user) {
			if ($user->role === "superadmin" || $user->role === "admin") {
				return true;
			}
		});

		$gate->define('contributor', function ($user) {
			if ($user->role === "superadmin" || $user->role === "builder" || $user->role === "admin" || $user->role === "contributor") {
				return true;
			}
		});

		$gate->define('reviewer', function ($user) {
			if ($user->role === "superadmin" || $user->role === "builder" || $user->role === "admin" || $user->role === "reviewer") {
				return true;
			}
		});

		$gate->define('builder', function ($user) {
			if ($user->role === "superadmin" || $user->role === "builder") {
				return true;
			}
		});
	}
}
