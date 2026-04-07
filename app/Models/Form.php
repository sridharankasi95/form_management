<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //
    protected $fillable = [
        'title',
        'status',  
    ];
    // one form has many form fields
    public function fields ()
    {
        return $this->hasMany(FormField::class);
    }
    // one form has many form submissions
    public function submissions ()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
