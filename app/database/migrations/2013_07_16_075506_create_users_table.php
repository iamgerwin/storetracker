<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tbl_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username');
			$table->string('employeeid');
			$table->string('password');
			$table->string('firstname');
			$table->string('middlename')->nullable();
			$table->string('lastname');
			$table->string('email');
			$table->integer('role')->default(3);
			$table->boolean('is_active')->default(1);
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
		Schema::drop('tbl_users');
	}

}
