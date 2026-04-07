<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    //
    protected $fillable = [
        'form_id',
        'data'
    ];
    protected $casts = [
        'data' => 'array',
    ];
    // Belongs to a Form
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
