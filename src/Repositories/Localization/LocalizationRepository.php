<?php namespace Sanatorium\Localization\Repositories\Localization;

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

}
