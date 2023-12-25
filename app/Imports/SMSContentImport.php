<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SMSContentImport implements ToCollection, WithHeadingRow, WithValidation
{
    public string $msg_table_name = "";
    public function __construct(string $msg_table_name){
        $this->msg_table_name = $msg_table_name;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string',
            // Define rules for other columns
        ];
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection) : void
    {
        foreach ($collection as $key => $row)
        {
            DB::connection("test_db_pgsql")->table($this->msg_table_name)->insert([
                "message" => $row["message"],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
    }

}
