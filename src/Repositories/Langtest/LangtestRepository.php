<?php namespace Sanatorium\Localization\Repositories\Langtest;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class LangtestRepository implements LangtestRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Localization\Handlers\Langtest\LangtestDataHandlerInterface
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

		$this->data = $app['sanatorium.localization.langtest.handler.data'];

		$this->setValidator($app['sanatorium.localization.langtest.validator']);

		$this->setModel(get_class($app['Sanatorium\Localization\Models\Langtest']));
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
		return $this->container['cache']->rememberForever('sanatorium.localization.langtest.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.localization.langtest.'.$id, function() use ($id)
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
		// Create a new langtest
		$langtest = $this->createModel();

		// Fire the 'sanatorium.localization.langtest.creating' event
		if ($this->fireEvent('sanatorium.localization.langtest.creating', [ $input ]) === false)
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
			// Save the langtest
			$langtest->fill($data)->save();

			// Fire the 'sanatorium.localization.langtest.created' event
			$this->fireEvent('sanatorium.localization.langtest.created', [ $langtest ]);
		}

		return [ $messages, $langtest ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the langtest object
		$langtest = $this->find($id);

		// Fire the 'sanatorium.localization.langtest.updating' event
		if ($this->fireEvent('sanatorium.localization.langtest.updating', [ $langtest, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($langtest, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the langtest
			$langtest->fill($data)->save();

			// Fire the 'sanatorium.localization.langtest.updated' event
			$this->fireEvent('sanatorium.localization.langtest.updated', [ $langtest ]);
		}

		return [ $messages, $langtest ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the langtest exists
		if ($langtest = $this->find($id))
		{
			// Fire the 'sanatorium.localization.langtest.deleted' event
			$this->fireEvent('sanatorium.localization.langtest.deleted', [ $langtest ]);

			// Delete the langtest entry
			$langtest->delete();

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
