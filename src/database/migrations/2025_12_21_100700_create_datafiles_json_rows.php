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
		Schema::create('datafiles_json_rows', function(Blueprint $table)
		{
			$table->id();
			$table->integer('row')->unsigned()->nullable();
			$table->unsignedBigInteger('datafile_id')->nullable();
            $table->string('datafile_sheet')->nullable();
            $table->string('datafile_type')->nullable();

            $table->unique(['datafile_id', 'datafile_sheet', 'row']);

            $table->mediumText('row_data')->nullable();

            $table->nullableTimestamps();
            $table->nullableOwnerships();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datafiles_json_rows');
	}

};
