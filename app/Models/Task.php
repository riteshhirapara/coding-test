<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'phase_id',
        'user_id',
        'due_date',
        'label_id',
        'completed_at'
    ];

    use HasFactory;

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    function labels()
    {
        return $this->belongsTo(Label::class, 'label_id', 'id');
    }

    function getDueDateAttribute (){
        // Get the raw date attribute from the database (assuming it's named 'created_at')
        $date = $this->attributes['created_at'];

        // Convert the date to the desired format
        return date('M d, Y \a\t H:i', strtotime($date));
    }
}
