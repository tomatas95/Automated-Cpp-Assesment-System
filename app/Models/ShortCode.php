<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortCode extends Model
{
    protected $fillable = [
        'shortcode',
        'replace',
    ];
    
    use HasFactory;
}
