<?php

namespace Gecche\Cupparis\DatafileJson\Breeze\Concerns;

use Gecche\Cupparis\DatafileJson\Rules\ExistsDatafileJson;
use Gecche\Cupparis\DatafileJson\Rules\UniqueDatafileJson;
use Gecche\Cupparis\DatafileJson\DatafileJsonServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

trait HasDatafileJsonValidation
{

    /*
     * Get the model validation rules, custom messages and custom attributes
     * to be used by an external validator instance
     */
    public function getDatafileModelValidationSettings($uniqueRules = true, $rules = [], $customMessages = [], $customAttributes = [])
    {
        $rules = array_merge(static::$rules, $rules);
        if ($this->getKey() && $uniqueRules) {
            $rules = $this->buildUniqueExclusionRules($rules);
        }

        $rules = array_merge($rules,$this->buildUniqueDatafileRules($rules,$this->getDatafileIdValue()));
        $rules = array_merge($rules,$this->buildExistsDatafileRules($rules,$this->getDatafileIdValue()));
        $validationData = [
            'rules' => $rules,
            'customMessages' => array_merge(static::$customMessages, $customMessages),
            'customAttributes' => array_merge(static::$customAttributes, $customAttributes),
        ];

        return $validationData;

    }

    /*
     * Get a full validator instance for the model
     */
    public
    function getDatafileValidator($data = null, $uniqueRules = true, $rules = [], $customMessages = [], $customAttributes = [])
    {
        $validatorSettings = $this->getDatafileModelValidationSettings($uniqueRules, $rules, $customMessages, $customAttributes);

        if (is_null($data)) {
            $rowData = $this->row_data;
            $data = Arr::get($rowData,'values',[]);
        }

        return Validator::make($data, $validatorSettings['rules'], $validatorSettings['customMessages'], $validatorSettings['customAttributes']);
    }


    protected
    function buildUniqueDatafileRules(array $rules = [], $datafile_id = null)
    {

        if (!$datafile_id) {
            $datafile_id = $this->getDatafileIdValue();
        }

        foreach ($rules as $field => &$ruleset) {
            // If $ruleset is a pipe-separated string, switch it to array
            $ruleset = (is_string($ruleset)) ? explode('|', $ruleset) : $ruleset;

            $ruleName = DatafileJsonServiceProvider::UNIQUE_DATAFILE_JSON_RULE;
            $ruleNameFull = $ruleName;
            foreach ($ruleset as &$rule) {
                if ($this->checkUniqueDatafileRule($rule)) {

                    $rule = new UniqueDatafileJson($datafile_id,$this->getDatafileSheetValue(),$this->datafile_type);

                }

            } // end foreach ruleset
        }

        return $rules;
    }


    protected
    function buildExistsDatafileRules(array $rules = [], $datafile_id = null)
    {

        if (!$datafile_id) {
            $datafile_id = $this->getDatafileIdValue();
        }

        foreach ($rules as $field => &$ruleset) {
            // If $ruleset is a pipe-separated string, switch it to array
            $ruleset = (is_string($ruleset)) ? explode('|', $ruleset) : $ruleset;

            $ruleName = DatafileJsonServiceProvider::EXISTS_DATAFILE_JSON_RULE;
            $ruleNameFull = $ruleName . ':';
            foreach ($ruleset as &$rule) {
                if ($this->checkExistsDatafileRule($rule)) {

                    $params = explode(',', substr($rule, strlen($ruleNameFull)), 3);

                    $rule = new ExistsDatafileJson($datafile_id,$this->getDatafileSheetValue(),$this->datafile_type,
                        Arr::get($params,0),Arr::get($params,1),Arr::get($params,2)
                    );
                } // end if strpos unique

            } // end foreach ruleset
        }

        return $rules;
    }


    protected function checkUniqueDatafileRule($rule) {
        if (is_string($rule)) {
            return Str::startsWith($rule,DatafileJsonServiceProvider::UNIQUE_DATAFILE_JSON_RULE);
        }
        return false;
    }

    protected function checkExistsDatafileRule($rule) {
        if (is_string($rule)) {
            return Str::startsWith($rule,DatafileJsonServiceProvider::EXISTS_DATAFILE_JSON_RULE);
        }
        return false;
    }

}
