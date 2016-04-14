<?php namespace Sanatorium\Localization\Repositories\Translations;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class TranslationsRepository implements TranslationsRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Localization\Handlers\Translations\TranslationsDataHandlerInterface
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

		$this->data = $app['sanatorium.localization.translations.handler.data'];

		$this->setValidator($app['sanatorium.localization.translations.validator']);

		$this->setModel(get_class($app['Sanatorium\Localization\Models\Translations']));
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
		return $this->container['cache']->rememberForever('sanatorium.localization.translations.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.localization.translations.'.$id, function() use ($id)
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
		// Create a new translations
		$translations = $this->createModel();

		// Fire the 'sanatorium.localization.translations.creating' event
		if ($this->fireEvent('sanatorium.localization.translations.creating', [ $input ]) === false)
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
			// Save the translations
			$translations->fill($data)->save();

			// Fire the 'sanatorium.localization.translations.created' event
			$this->fireEvent('sanatorium.localization.translations.created', [ $translations ]);
		}

		return [ $messages, $translations ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the translations object
		$translations = $this->find($id);

		// Fire the 'sanatorium.localization.translations.updating' event
		if ($this->fireEvent('sanatorium.localization.translations.updating', [ $translations, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($translations, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the translations
			$translations->fill($data)->save();

			// Fire the 'sanatorium.localization.translations.updated' event
			$this->fireEvent('sanatorium.localization.translations.updated', [ $translations ]);
		}

		return [ $messages, $translations ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the translations exists
		if ($translations = $this->find($id))
		{
			// Fire the 'sanatorium.localization.translations.deleted' event
			$this->fireEvent('sanatorium.localization.translations.deleted', [ $translations ]);

			// Delete the translations entry
			$translations->delete();

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

}
