<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'question_id',
        'search_id',
        'title', 
        'link', 
        'creation_date'
    ];

    public function search()
    {
        return $this->belongsTo(Search::class, 'search_id');
    }
}
