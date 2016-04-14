<?php namespace Sanatorium\Localization\Handlers\Translations;

use Sanatorium\Localization\Models\Translations;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface TranslationsEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a translations is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a translations is created.
	 *
	 * @param  \Sanatorium\Localization\Models\Translations  $translations
	 * @return mixed
	 */
	public function created(Translations $translations);

	/**
	 * When a translations is being updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Translations  $translations
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Translations $translations, array $data);

	/**
	 * When a translations is updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Translations  $translations
	 * @return mixed
	 */
	public function updated(Translations $translations);

	/**
	 * When a translations is deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Translations  $translations
	 * @return mixed
	 */
	public function deleted(Translations $translations);

}
