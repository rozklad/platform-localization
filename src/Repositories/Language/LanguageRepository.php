<?php namespace Sanatorium\Localization\Repositories\Language;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class LanguageRepository implements LanguageRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Localization\Handlers\Language\LanguageDataHandlerInterface
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

		$this->data = $app['sanatorium.localization.language.handler.data'];

		$this->setValidator($app['sanatorium.localization.language.validator']);

		$this->setModel(get_class($app['Sanatorium\Localization\Models\Language']));
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
		return $this->container['cache']->rememberForever('sanatorium.localization.language.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.localization.language.'.$id, function() use ($id)
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
		// Create a new language
		$language = $this->createModel();

		// Fire the 'sanatorium.localization.language.creating' event
		if ($this->fireEvent('sanatorium.localization.language.creating', [ $input ]) === false)
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
			// Save the language
			$language->fill($data)->save();

			// Fire the 'sanatorium.localization.language.created' event
			$this->fireEvent('sanatorium.localization.language.created', [ $language ]);
		}

		return [ $messages, $language ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the language object
		$language = $this->find($id);

		// Fire the 'sanatorium.localization.language.updating' event
		if ($this->fireEvent('sanatorium.localization.language.updating', [ $language, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($language, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the language
			$language->fill($data)->save();

			// Fire the 'sanatorium.localization.language.updated' event
			$this->fireEvent('sanatorium.localization.language.updated', [ $language ]);
		}

		return [ $messages, $language ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the language exists
		if ($language = $this->find($id))
		{
			// Fire the 'sanatorium.localization.language.deleted' event
			$this->fireEvent('sanatorium.localization.language.deleted', [ $language ]);

			// Delete the language entry
			$language->delete();

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
