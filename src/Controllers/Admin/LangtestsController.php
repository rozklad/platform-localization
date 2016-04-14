<?php namespace Sanatorium\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Localization\Repositories\Langtest\LangtestRepositoryInterface;

class LangtestsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Localization repository.
	 *
	 * @var \Sanatorium\Localization\Repositories\Langtest\LangtestRepositoryInterface
	 */
	protected $langtests;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Localization\Repositories\Langtest\LangtestRepositoryInterface  $langtests
	 * @return void
	 */
	public function __construct(LangtestRepositoryInterface $langtests)
	{
		parent::__construct();

		$this->langtests = $langtests;
	}

	/**
	 * Display a listing of langtest.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/localization::langtests.index');
	}

	/**
	 * Datasource for the langtest Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->langtests->grid();

		$columns = [
			'id',
			'code',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.localization.langtests.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new langtest.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new langtest.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating langtest.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating langtest.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified langtest.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->langtests->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/localization::langtests/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.localization.langtests.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->langtests->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a langtest identifier?
		if (isset($id))
		{
			if ( ! $langtest = $this->langtests->find($id))
			{
				$this->alerts->error(trans('sanatorium/localization::langtests/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.localization.langtests.all');
			}
		}
		else
		{
			$langtest = $this->langtests->createModel();
		}

		// Show the page
		return view('sanatorium/localization::langtests.form', compact('mode', 'langtest'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the langtest
		list($messages) = $this->langtests->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/localization::langtests/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.localization.langtests.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
