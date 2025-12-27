<?php

namespace Gecche\Cupparis\DatafileJson\Breeze\Concerns;

use Gecche\Cupparis\DatafileJson\Models\DatafileJsonUniqueValue;
use Gecche\Cupparis\DatafileJson\Rules\ExistsDatafileJson;
use Gecche\Cupparis\DatafileJson\Rules\UniqueDatafileJson;
use Gecche\Cupparis\DatafileJson\DatafileJsonServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

trait UniqueValuesDatafileQueries
{

    public function getDatafileUniqueValue($value,$field,$datafileId,$datafileSheet,$datafileType,$rowIndex) {
        return DB::table('datafiles_json_unique_values')
            ->where('value', $value)
            ->where('field', $field)
            ->where('datafile_id', $datafileId)
            ->where('datafile_sheet', $datafileSheet)
            ->where('datafile_type', $datafileType)
            ->first();
    }

    public function setDatafileUniqueValue($value,$field,$datafileId,$datafileSheet,$datafileType,$rowIndex) {
        $uniqueData = [
            'field' => $field,
            'datafile_id' => $datafileId,
            'datafile_sheet' => $datafileSheet,
            'datafile_type' => $datafileType,
            'value' => $value,
            'n_rows' => 1,
            'rows' => cupparis_json_encode([$rowIndex]),
        ];

        try {
            DB::table('datafiles_json_unique_values')
                ->insert($uniqueData);
        } catch (\Throwable $e) {
            try {
                $record = $this->getDatafileUniqueValue($value,$field,$datafileId,$datafileSheet,$datafileType,$rowIndex);
                if ($record) {
                    $nRows = $record->n_rows+1;
                    $rows = json_decode($record->rows,true);
                    $rows[] = $rowIndex;
                    $rows = cupparis_json_encode($rows);
                    DB::table('datafiles_json_unique_values')
                        ->where('id',$record->id)
                        ->update(['n_rows' => $nRows,'rows' => $rows]);
                }
            } catch (\Throwable $eUpdate) {

            }
        }
    }

}
