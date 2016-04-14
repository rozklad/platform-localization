<?php namespace Sanatorium\Localization\Handlers\Language;

use Sanatorium\Localization\Models\Language;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface LanguageEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a language is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a language is created.
	 *
	 * @param  \Sanatorium\Localization\Models\Language  $language
	 * @return mixed
	 */
	public function created(Language $language);

	/**
	 * When a language is being updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Language  $language
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Language $language, array $data);

	/**
	 * When a language is updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Language  $language
	 * @return mixed
	 */
	public function updated(Language $language);

	/**
	 * When a language is deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Language  $language
	 * @return mixed
	 */
	public function deleted(Language $language);

}
