<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarNote extends Model
{
    protected $table = 'calendar_notes';

    protected $fillable = [
        'id_user', 'title', 'description', 'date', 'color'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}