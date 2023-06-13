<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Set;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class QuestionImport implements ToCollection, WithHeadingRow
{
    use Importable;

    private $setId;

    public function __construct($setName, $subject_id, $board_id, $assign_class_id,$lesson_id, $teacher_id)
    {
        $this->setName = $setName;
        $this->assign_subject_id = $subject_id;
        $this->board_id = $board_id;
        $this->assign_class_id = $assign_class_id;
        $this->lesson_id=$lesson_id;
        $this->teacher_id=$teacher_id;
    }

    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.question' => 'required',
            '*.option_1' => 'required',
            '*.option_2' => 'required',
            '*.option_3' => 'required',
            '*.option_4' => 'required',
            '*.correct_answer' => 'required',
        ])->validate();



        $set=Set::create([
            'set_name' =>  $this->setName,
            'assign_subject_id' => $this->assign_subject_id,
            'board_id' => $this->board_id,
            'assign_class_id' => $this->assign_class_id,
            'lesson_id'=>$this->lesson_id,
            'teacher_id'=>$this->teacher_id,
        ]);

        foreach ($rows as $row) {
            Question::create([
                'question' => $row['question'],
                'option_1' => $row['option_1'],
                'option_2' => $row['option_2'],
                'option_3' => $row['option_3'],
                'option_4' => $row['option_4'],
                'correct_answer' => $row['correct_answer'],
                'set_id' => (int) $set->id
            ]);
        }
    }
}
