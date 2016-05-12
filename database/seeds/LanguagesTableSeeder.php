<?php namespace Sanatorium\Localization\Database\Seeds;

use DB;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('languages')->truncate();

		$languages = [
			[
				'locale' => 'cs',
				'name' => 'Čeština',
				'created_at' => date('Y-m-d H:i:s'),
			],
			[
				'locale' => 'en',
				'name' => 'English',
				'created_at' => date('Y-m-d H:i:s'),
			],
			[
				'locale' => 'fr',
				'name' => 'Francais',
				'created_at' => date('Y-m-d H:i:s'),
			],
			[
				'locale' => 'de',
				'name' => 'Deutsch',
				'created_at' => date('Y-m-d H:i:s'),
			],
		];

		foreach($languages as $language)
		{
			DB::table('languages')->insert($language);
		}
	}

}
