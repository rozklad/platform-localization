<?php namespace Sanatorium\Localization\Handlers\Localization;

use Sanatorium\Localization\Models\Localization;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface LocalizationEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a localization is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a localization is created.
	 *
	 * @param  \Sanatorium\Localization\Models\Localization  $localization
	 * @return mixed
	 */
	public function created(Localization $localization);

	/**
	 * When a localization is being updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Localization  $localization
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Localization $localization, array $data);

	/**
	 * When a localization is updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Localization  $localization
	 * @return mixed
	 */
	public function updated(Localization $localization);

	/**
	 * When a localization is being deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Localization  $localization
	 * @return mixed
	 */
	public function deleting(Localization $localization);

	/**
	 * When a localization is deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Localization  $localization
	 * @return mixed
	 */
	public function deleted(Localization $localization);

}
