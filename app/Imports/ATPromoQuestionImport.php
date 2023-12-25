<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ATPromoQuestionImport implements ToCollection, WithValidation, WithHeadingRow
{

    public function rules(): array
    {
        return [
            'question' => 'required|string',
            'answer' => 'required|string',
        ];
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            Log::info($row);
            DB::connection("at_mega_promo")->table("at_promo_msg")->insert([
                "question" => $row["question"],
                "possible_answer" => $row["answer"],
                "answer" => $row["answer"],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
    }
}
