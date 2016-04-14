<?php namespace Sanatorium\Localization\Traits;

use Platform\Attributes\Traits\EntityTrait;
//use Cartalyst\Attributes\EntityTrait;

trait LocalizedEntityTrait {

	use EntityTrait, TranslatableTrait {

		EntityTrait::save as saveAttributes;
		TranslatableTrait::save as saveTranslation;

		EntityTrait::delete as deleteAttributes;
		TranslatableTrait::delete as deleteTranslations;

	}

	/**
	 * {@inheritDoc}
	 */
	public function save(array $options = array(), $locale = null)
	{
		$this->locale = $locale;

		// If we have a locale set, we are dealing with
		// a translation, otherwise a regular model.
		if ($this->locale)
		{
			return $this->saveTranslation($options);
		}

		return $this->saveAttributes($options);
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete()
	{
		// We will delete the translations first, then
		// continue with the attributes delete method.
		$this->deleteTranslations();

		return $this->deleteAttributes();
	}

}