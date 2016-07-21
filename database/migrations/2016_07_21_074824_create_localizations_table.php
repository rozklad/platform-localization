<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('localizations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('locale');
			$table->integer('entity_id');
			$table->string('entity_field');
			$table->string('entity_type');
			$table->text('entity_value');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('localizations');
	}

}
