<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    protected $fillable = ['template_id','row_number','column_number','creator_id','approver_id','status','comment'];
    protected $guarded = [];
	protected $table = 't_changerequests';

    public function drafttechnical()
    {
        return $this->belongsTo('App\DraftTechnical');
    }
	
    public function draftrequirement()
    {
        return $this->hasMany('App\DraftRequirement');
    }
	
    public function draftfield()
    {
        return $this->hasMany('App\DraftField');
    }
	
    public function template()
    {
		return $this->hasOne('App\Template', 'id', 'template_id');
    }	
	
}

?>
