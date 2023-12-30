<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VFPromoQuestionImport implements ToCollection, WithHeadingRow, WithValidation
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
            // Log::info($row);
            DB::connection("vf_connection")->table("PromoMsg")->insert([
                "Question" => $row["question"],
                "PossibleAnswers" => $row["answer"],
                "Answers" => $row["answer"],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
    }
}
