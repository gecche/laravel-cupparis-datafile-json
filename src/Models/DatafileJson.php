<?php

namespace Gecche\Cupparis\DatafileJson\Models;

use Gecche\Breeze\Breeze;

class DatafileJson extends Breeze {

	protected $table = 'datafiles_json';

	protected $guarded = ['id'];

    public static $relationsData = [];

    public $timestamps = true;
    public $ownerships = true;

    public $casts = [
        'datafile_sheet' => 'array',
    ];

}
