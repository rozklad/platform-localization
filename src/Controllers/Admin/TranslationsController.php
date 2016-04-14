<?php namespace Sanatorium\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Localization\Repositories\Translations\TranslationsRepositoryInterface;

class TranslationsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Localization repository.
	 *
	 * @var \Sanatorium\Localization\Repositories\Translations\TranslationsRepositoryInterface
	 */
	protected $translations;

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
	 * @param  \Sanatorium\Localization\Repositories\Translations\TranslationsRepositoryInterface  $translations
	 * @return void
	 */
	public function __construct(TranslationsRepositoryInterface $translations)
	{
		parent::__construct();

		$this->translations = $translations;
	}

	/**
	 * Display a listing of translations.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/localization::translations.index');
	}

	/**
	 * Datasource for the translations Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->translations->grid();

		$columns = [
			'id',
			'namespace',
			'locale',
			'entity_id',
			'entity_field',
			'entity_value',
		];

		$settings = [
			'sort'      => 'id',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.localization.translations.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new translations.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new translations.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating translations.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating translations.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified translations.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->translations->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/localization::translations/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.localization.translations.all');
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
				$this->translations->{$action}($row);
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
		// Do we have a translations identifier?
		if (isset($id))
		{
			if ( ! $translations = $this->translations->find($id))
			{
				$this->alerts->error(trans('sanatorium/localization::translations/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.localization.translations.all');
			}
		}
		else
		{
			$translations = $this->translations->createModel();
		}

		// Show the page
		return view('sanatorium/localization::translations.form', compact('mode', 'translations'));
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
		// Store the translations
		list($messages) = $this->translations->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/localization::translations/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.localization.translations.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
