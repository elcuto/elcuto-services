<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Renewal extends Model
{
    use HasFactory;

    protected $table='Renewals';

    protected $guarded=['id'];

    public static function createRenewal(Request $request){
        try{
            Renewal::create($request->all());
            return 'OK';
        }catch(Exception $e){
            // Log::info("+++++++UNABLE TO CREATE RENEWAL+++++++");
            // Log::info($e);
            // Log::info("+++++++UNABLE TO CREATE RENEWAL+++++++");
            return 'ERR';
        }
    }
}
