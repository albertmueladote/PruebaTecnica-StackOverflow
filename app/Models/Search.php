<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'fromdate', 
        'todate', 
        'tag', 
        'creation_date'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'search_id');
    }
}
