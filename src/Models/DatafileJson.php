<?php

namespace Gecche\Cupparis\DatafileJson\Models;

use Gecche\Breeze\Breeze;
use Illuminate\Support\Facades\DB;

class DatafileJson extends Breeze {

	protected $table = 'datafiles_json';

	protected $guarded = ['id'];

    public static $relationsData = [];

    public $timestamps = true;
    public $ownerships = true;

    public $casts = [
        'datafile_sheet' => 'array',
    ];

    public function getHasErrorsAttribute() {

        $record = DB::table('datafiles_json_rows')
            ->where('datafile_id',$this->datafile_id)
            ->where('datafile_sheet',$this->datafile_sheet)
            ->where('datafile_type',$this->datafile_type)
            ->where('has_errors',1)
            ->first();

        return (bool) $record;

    }

}
