<?php

namespace Gecche\Cupparis\DatafileJson\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\DatabaseRule;
use Illuminate\Validation\Validator;

class UniqueDatafileJson implements ValidationRule
{


    protected $datafileId;
    protected $datafileSheet;
    protected $datafileType;


    public function __construct($datafileId,$datafileSheet,$datafileType)
    {
        $this->datafileId = $datafileId;
        $this->datafileSheet = $datafileSheet;
        $this->datafileType = $datafileType;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $record = DB::table('datafiles_json_unique_values')
            ->where('value',$value)
            ->where('field',$attribute)
            ->where('datafile_id',$this->datafileId)
            ->where('datafile_sheet',$this->datafileSheet)
            ->where('datafile_type',$this->datafileType)
            ->first();

        $uniqueData = [
            'field' => $attribute,
            'datafile_id' => $this->datafileId,
            'datafile_sheet' => $this->datafileSheet,
            'datafile_type' => $this->datafileType,
            'value' => $value,
        ];
        try {
            DB::table('datafiles_json_unique_values')
                ->insert($uniqueData);
        } catch (\Throwable $e) {

        }

        if ($record) {
            $fail("validation.unique_datafile_json")->translate();
        };


    }


}
