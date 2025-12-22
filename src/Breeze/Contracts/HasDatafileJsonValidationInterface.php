<?php namespace Gecche\Cupparis\DatafileJson\Breeze\Contracts;

/**
 * Breeze - Eloquent model base class with some pluses!
 *
 */
interface  HasDatafileJsonValidationInterface {


    public function getDatafileModelValidationSettings($uniqueRules = true, $rules = [], $customMessages = [], $customAttributes = []);

    public
    function getDatafileValidator($data = null, $uniqueRules = true, $rules = [], $customMessages = [], $customAttributes = []);


}
