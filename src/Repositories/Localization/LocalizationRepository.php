<?php namespace Sanatorium\Localization\Repositories\Localization;

use App;
use Cache;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class LocalizationRepository implements LocalizationRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Localization\Handlers\Localization\LocalizationDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent localization model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.localization.localization.handler.data'];

		$this->setValidator($app['sanatorium.localization.localization.validator']);

		$this->setModel(get_class($app['Sanatorium\Localization\Models\Localization']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.localization.localization.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.localization.localization.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new localization
		$localization = $this->createModel();

		// Fire the 'sanatorium.localization.localization.creating' event
		if ($this->fireEvent('sanatorium.localization.localization.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the localization
			$localization->fill($data)->save();

			// Fire the 'sanatorium.localization.localization.created' event
			$this->fireEvent('sanatorium.localization.localization.created', [ $localization ]);
		}

		return [ $messages, $localization ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the localization object
		$localization = $this->find($id);

		// Fire the 'sanatorium.localization.localization.updating' event
		if ($this->fireEvent('sanatorium.localization.localization.updating', [ $localization, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($localization, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the localization
			$localization->fill($data)->save();

			// Fire the 'sanatorium.localization.localization.updated' event
			$this->fireEvent('sanatorium.localization.localization.updated', [ $localization ]);
		}

		return [ $messages, $localization ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the localization exists
		if ($localization = $this->find($id))
		{
			// Fire the 'sanatorium.localization.localization.deleting' event
			$this->fireEvent('sanatorium.localization.localization.deleting', [ $localization ]);

			// Delete the localization entry
			$localization->delete();

			// Fire the 'sanatorium.localization.localization.deleted' event
			$this->fireEvent('sanatorium.localization.localization.deleted', [ $localization ]);

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

    /**
     * Base cache key for localization
     * @var string
     */
	public static $default_cache_key = 'localize_repository';

    /**
     * Get entity key localization from database
     *
     * @param $locale
     * @param $entity_id
     * @param $entity_field
     * @param $entity_type
     * @return bool
     */
    public function getLocalizationValue($locale, $entity_id, $entity_field, $entity_type)
    {
        $translation = app('sanatorium.localization.localization')->where('locale', $locale)
            ->where('entity_id', $entity_id)
            ->where('entity_field', $entity_field)
            ->where('entity_type', $entity_type)
            ->first();

        if ( $translation )
        {
            return $translation->entity_value;
        }

        return false;
    }

    /**
     * Get entity key localization
     *
     * @param null $object  Entity object (f.e. instance of Platform\Pages\Models\Page)
     * @param null $key     Entity key (f.e. "meta_title")
     * @param null $locale  Locale code (f.e. "de")
     * @param null $default_cache_key   Default string for building cache key
     * @param bool $use_fallback        Allow fallback (TRUE=use non-translated value of localization is not available)
     * @param bool $cache               Allow cache
     * @return string|null
     */
	public function get(
	    $object = null,
        $key = null,
        $locale = null,
        $default_cache_key = null,
        $use_fallback = false,
        $cache = true)
    {
        $fallback = $object->{$key};

        $locale = isset($locale) ? $locale : App::getLocale();

        $entity_id = $object->id;
        $entity_type = get_class($object);
        $entity_field = $key;

        $cache_key = self::getCacheKey($locale, $entity_type, $entity_id, $entity_field, $default_cache_key);

        if ( $cache && $value = Cache::get($cache_key) )
        {
            // If cached value is available
        } else if ( $value = $this->getLocalizationValue($locale, $entity_id, $entity_field, $entity_type) )
        {
            // If value is found in database
        } else if ( $use_fallback )
        {
            // If value is not available at all
            $value = $fallback;
        } else {
            $value = null;
        }

        Cache::forever($cache_key, $value);

        return $value;
    }

    public function set(
        $object,
        $key,
        $locale,
        $entity_value = null,
        $default_cache_key = null)
    {
        if ( !is_object($object) )
            return false;

        $entity_id = $object->id;
        $entity_type = get_class($object);
        $entity_field = $key;

        $cache_key = self::getCacheKey($locale, $entity_type, $entity_id, $entity_field, $default_cache_key);

        // Find localization for the given setup or create
        $localization = app('sanatorium.localization.localization')->firstOrCreate([
            'entity_id'     => $entity_id,
            'entity_type'   => $entity_type,
            'entity_field'  => $entity_field,
            'locale'        => $locale
        ]);

        $localization->entity_value = $entity_value;

        // Forget cache key
        Cache::forget($cache_key);

        return $localization->save();
    }

    public static function getCacheKey($locale, $entity_type, $entity_id, $entity_field, $default_cache_key = null)
    {
        $default_cache_key = is_null($default_cache_key) ? $default_cache_key : self::$default_cache_key;

        return implode('.', [$default_cache_key, $locale, $entity_type, $entity_id, $entity_field]);
    }

}
