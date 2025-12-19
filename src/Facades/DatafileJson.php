<?php namespace Gecche\Cupparis\DatafileJson\Facades;

use Illuminate\Support\Facades\Facade as Facade;

class DatafileJson extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'datafile-json'; }

}