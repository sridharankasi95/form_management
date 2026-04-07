<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    //
    protected $fillable = [
        'form_id',
        'label',
        'type',
        'required',
        'validation_rules',
        'options',
        'order'
    ];
        // Auto convert json to array
    protected $casts = [
        'options'          => 'array',
        'validation_rules' => 'array',
        'required'         => 'boolean',
    ];

    // Field belongs to a Form
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
