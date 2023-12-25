<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSSent extends Model
{
    use HasFactory;

    protected $table = "SMSSentTable";
    
    protected $guarded = ["id"];
}
