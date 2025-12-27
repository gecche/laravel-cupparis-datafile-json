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
    protected $rowIndex;


    public function __construct($datafileId,$datafileSheet,$datafileType,$rowIndex)
    {
        $this->datafileId = $datafileId;
        $this->datafileSheet = $datafileSheet;
        $this->datafileType = $datafileType;
        $this->rowIndex = $rowIndex;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $record = $this->getDatafileUniqueValue($value,$attribute,$this->datafileId,$this->datafileSheet,$this->datafileType,$this->rowIndex);

        $this->setDatafileUniqueValue($value,$attribute,$this->datafileId,$this->datafileSheet,$this->datafileType,$this->rowIndex);


        if ($record) {
            $fail("validation.unique_datafile_json")->translate();
        };


    }


}
