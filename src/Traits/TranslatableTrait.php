<?php namespace Sanatorium\Localization\Traits;

use Cartalyst\Support\Traits\NamespacedEntityTrait;

trait TranslatableTrait {

	use NamespacedEntityTrait;

	/**
	 * Indicates whether the model is
	 * a translation or not.
	 *
	 * @var bool
	 */
	public $isTranslation = false;

	/**
	 * Localized attributes.
	 *
	 * @var array
	 */
	protected $localized = [];

	/**
	 * Entity primary key.
	 *
	 * @var array
	 */
	protected $entityId;

	/**
	 * Model locale.
	 *
	 * @var array
	 */
	protected $locale;

	/**
	 * Model namespace.
	 *
	 * @var array
	 */
	protected $namespace;

	/**
	 * The translation model name.
	 *
	 * @var string
	 */
	protected static $translationModel = 'Sanatorium\Localization\Models\Translation';

	/**
	 * Creates a new translated model.
	 *
	 * @param  string  $locale
	 * @return this
	 */
	public function translation($locale)
	{
		$default = config('sanatorium-localization.default');

		// If we are retriving the default locale,
		// we will return the original model.
		if ($locale === $default)
		{
			return $this->getOriginalModel();
		}

		$model = $this;

		// If we are dealing with a translated model,
		// we want to retrieve the original model to
		// fetch the required attributes from.
		if ($this->isTranslation)
		{
			$model = $this->getOriginalModel();
		}

		$translationModel = new static;

		// Re-assign existing relationships.
		$translationModel->setRelations($model->getRelations());

		// Retrieve existing translations and
		// assign them to our model.
		$translations = $model->translations()->locale($locale)->get();

		foreach ($translations as $item)
		{
			$translationModel->setAttribute($item->entity_field, $item->entity_value);
 		}

 		// Localization attributes.
		$translationModel->isTranslation = true;
		$translationModel->locale        = $locale;
		$translationModel->entityId      = $model->getKey();
		$translationModel->namespace     = $model->getEntityNamespace();

		return $translationModel;
	}

	/**
	 * Returns the translation model name.
	 *
	 * @return string
	 */
	public static function getTranslationModel()
	{
		return static::$translationModel;
	}

	/**
	 * Sets the translation model name.
	 *
	 * @param  string  $model
	 * @return void
	 */
	public static function setTranslationModel($model)
	{
		static::$translationModel = $model;
	}

	/**
	 * Returns the entity translations.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function translations()
	{
		return $this->hasMany(
			$this->getTranslationModel(),
			$this->getTranslatableForeignKey()
		)->whereNamespace($this->getEntityNamespace());
	}

	/**
	 * Returns the translatable relation foreign key.
	 *
	 * @return string
	 */
	public function getTranslatableForeignKey()
	{
		return isset($this->translatableForeignKey) ? $this->translatableForeignKey : 'entity_id';
	}

	/**
	 * Checks if there is a translation available for the given locale.
	 *
	 * @param  string  $locale
	 * @return bool
	 */
	public function hasTranslation($locale)
	{
		return (bool) $this->translations()->locale($locale)->first();
	}

	/**
	 * Returns all the entity translated locales.
	 *
	 * @return array
	 */
	public function getTranslatedLocales()
	{
		return array_unique($this->translations()->lists('locale'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function __get($key)
	{
		$default = config('sanatorium-localization.default');
		$lang    = app('translator')->getLocale();

		if ($default === $lang)
		{
			return parent::__get($key);
		}

		$trans = $this->translation(app('translator')->getLocale());

		return $trans->getAttribute($key) ?: parent::__get($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function __set($key, $value)
	{
		$default = config('sanatorium-localization.default');

		if ($this->locale === $default)
		{
			return parent::__set($key, $value);
		}

		$this->localized[$key] = $value;

		return parent::__set($key, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function save(array $options = [])
	{
		$default = config('sanatorium-localization.default');
		$lang    = app('translator')->getLocale();

		if ($this->locale === $default || ! $this->locale)
		{
			return parent::save($options);
		}

		$attributes = $this->getAttributes();

		$langModel = $this;

		if ( ! $this->isTranslation)
		{
			$langModel = $this->translation($lang);
		}

		$translationModel = $this->getTranslationModel();

		// Loop through localized attributes
		// and update or store them.
		foreach ($this->localized as $key => $value)
		{
			$current = (new $translationModel)
				->locale($langModel->locale)
				->namespace($langModel->namespace)
				->field($key)
				->entity($langModel->entityId)
				->first();

			if ( ! $current)
			{
				$current = new $translationModel;
			}

			$current->entity_field = $key;
			$current->entity_value = $value;
			$current->locale       = $langModel->locale;
			$current->namespace    = $langModel->namespace;
			$current->entity_id    = $langModel->entityId;

		 	$current->save();
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete()
	{
		// If we are dealing with the original model,
		// we will delete all translations right after
		// the model.
		if ($this->exists)
		{
			if (parent::delete())
			{
				$this->removeTranslations();

				return true;
			}
		}

		// Otherwise, we will flush the translations
		// only and leave the model untouched.
		else
		{
			$this->removeTranslation();
		}
	}

	/**
	 * Deletes all translations that are
	 * attached to this model.
	 *
	 * @return void
	 */
	public function removeTranslations()
	{
		$translationModel = $this->getTranslationModel();

		$model = new $translationModel;

		$model
			->namespace(get_class($this))
			->entity($this->getKey())
			->delete();
	}

	/**
	 * Deletes translations that are
	 * attached to this model.
	 *
	 * @return void
	 */
	public function removeTranslation()
	{
		$translationModel = $this->getTranslationModel();

		$model = new $translationModel;

		$model
			->locale($this->locale)
			->namespace($this->namespace)
			->entity($this->entityId)
			->delete();
	}

	/**
	 * Finds the original model.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return \Illuminate\Database\Eloquent\Model|static
	 */
	public function getOriginalModel()
	{
		return $this->find($this->entityId);
	}

}