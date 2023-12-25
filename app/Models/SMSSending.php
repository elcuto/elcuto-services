<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSSending extends Model
{
    use HasFactory;

    protected $table = "SMSSendingTable";
    
    protected $guarded = ["id"];
}
