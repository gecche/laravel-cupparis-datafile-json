<?php

namespace Gecche\Cupparis\DatafileJson\Models\Relations;

use Gecche\Cupparis\DatafileJson\Models\DatafileJson;

trait DatafileJsonRowRelations
{

    public function fields() {

        return $this->morphTo('datafile');
    
    }



}
