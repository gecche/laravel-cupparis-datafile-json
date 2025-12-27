<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datafiles_json_unique_values', function(Blueprint $table)
		{
            $table->id();
            $table->string('field')->nullable();
            $table->string('value')->nullable();
            $table->unsignedBigInteger('datafile_id')->nullable();
            $table->string('datafile_sheet')->nullable();
            $table->string('datafile_type')->nullable();

            $table->unique(['datafile_id', 'datafile_sheet', 'field', 'value'],'dfj_uniques');

            $table->mediumText('rows')->nullable();
            $table->unsignedInteger('n_rows')->default(0);

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datafiles_json_unique_values');
	}

};
