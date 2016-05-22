<?php namespace Sanatorium\Localization\Handlers\Translation;

use Sanatorium\Localization\Models\Translation;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface TranslationEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a translation is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a translation is created.
	 *
	 * @param  \Sanatorium\Localization\Models\Translation  $translation
	 * @return mixed
	 */
	public function created(Translation $translation);

	/**
	 * When a translation is being updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Translation  $translation
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Translation $translation, array $data);

	/**
	 * When a translation is updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Translation  $translation
	 * @return mixed
	 */
	public function updated(Translation $translation);

	/**
	 * When a translation is being deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Translation  $translation
	 * @return mixed
	 */
	public function deleting(Translation $translation);

	/**
	 * When a translation is deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Translation  $translation
	 * @return mixed
	 */
	public function deleted(Translation $translation);

}
