<?php namespace Sanatorium\Localization\Handlers\Langtest;

use Sanatorium\Localization\Models\Langtest;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface LangtestEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a langtest is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a langtest is created.
	 *
	 * @param  \Sanatorium\Localization\Models\Langtest  $langtest
	 * @return mixed
	 */
	public function created(Langtest $langtest);

	/**
	 * When a langtest is being updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Langtest  $langtest
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Langtest $langtest, array $data);

	/**
	 * When a langtest is updated.
	 *
	 * @param  \Sanatorium\Localization\Models\Langtest  $langtest
	 * @return mixed
	 */
	public function updated(Langtest $langtest);

	/**
	 * When a langtest is deleted.
	 *
	 * @param  \Sanatorium\Localization\Models\Langtest  $langtest
	 * @return mixed
	 */
	public function deleted(Langtest $langtest);

}
