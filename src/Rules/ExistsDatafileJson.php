<?php

namespace Gecche\Cupparis\DatafileJson\Rules;

use Closure;
use Gecche\Cupparis\DatafileJson\Breeze\Concerns\UniqueValuesDatafileQueries;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\DatabaseRule;
use Illuminate\Validation\Validator;

class ExistsDatafileJson implements ValidationRule
{

    use UniqueValuesDatafileQueries;

    protected $datafileId;
    protected $datafileSheet;
    protected $datafileType;

    protected $rowIndex;

    protected $datafileField;
    protected $dbTable;
    protected $dbField;

    public function __construct($datafileId, $datafileSheet, $datafileType,$datafileField, $rowIndex, $dbTable = null, $dbField = null)
    {
        $this->datafileId = $datafileId;
        $this->datafileSheet = $datafileSheet;
        $this->datafileType = $datafileType;
        $this->datafileField = $datafileField;
        $this->rowIndex = $rowIndex;
        $this->dbTable = $dbTable;
        $this->dbField = $dbField;
    }


    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recordDb = DB::table($this->dbTable)
            ->where($this->dbField, $value)
            ->first();
        if (!$recordDb) {
            $record = $this->getDatafileUniqueValue($value,$attribute,$this->datafileId,$this->datafileSheet,$this->datafileType,$this->rowIndex);
        }

        $this->setDatafileUniqueValue($value,$attribute,$this->datafileId,$this->datafileSheet,$this->datafileType,$this->rowIndex);

        if (!$record) {
            $fail("validation.exists_datafile_json")->translate();
        };


    }


}
