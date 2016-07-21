<?php namespace Sanatorium\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Localization\Repositories\Localization\LocalizationRepositoryInterface;
use Sanatorium\Localization\Repositories\Language\LanguageRepositoryInterface;

class LocalizationsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Localization repository.
	 *
	 * @var \Sanatorium\Localization\Repositories\Localization\LocalizationRepositoryInterface
	 */
	protected $localizations;

    /**
     * The Localization repository.
     *
     * @var \Sanatorium\Localization\Repositories\Language\LanguageRepositoryInterface
     */
    protected $languages;

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
	 * @param  \Sanatorium\Localization\Repositories\Localization\LocalizationRepositoryInterface  $localizations
     * @param  \Sanatorium\Localization\Repositories\Language\LanguageRepositoryInterface  $languages
	 * @return void
	 */
	public function __construct(LocalizationRepositoryInterface $localizations,
                                LanguageRepositoryInterface $languages)
	{
		parent::__construct();

		$this->localizations = $localizations;

        $this->languages = $languages;
	}

	/**
	 * Display a listing of localization.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/localization::localizations.index');
	}

	/**
	 * Datasource for the localization Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->localizations->grid();

		$columns = [
			'id',
			'locale',
			'entity_id',
			'entity_field',
			'entity_type',
			'entity_value',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.localization.localizations.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new localization.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new localization.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating localization.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating localization.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified localization.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->localizations->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/localization::localizations/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.localization.localizations.all');
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
				$this->localizations->{$action}($row);
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
		// Do we have a localization identifier?
		if (isset($id))
		{
			if ( ! $localization = $this->localizations->find($id))
			{
				$this->alerts->error(trans('sanatorium/localization::localizations/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.localization.localizations.all');
			}
		}
		else
		{
			$localization = $this->localizations->createModel();
		}

		$locales = $this->languages->lists('locale')->toArray();

        $localized = app('sanatorium.localization.localized')->get();

		// Show the page
		return view('sanatorium/localization::localizations.form', compact('mode', 'localization', 'locales', 'localized'));
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
		// Store the localization
		list($messages) = $this->localizations->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/localization::localizations/message.success.{$mode}"));

            if ( $mode == 'create' )
                return redirect()->route('admin.sanatorium.localization.localizations.create');

			return redirect()->route('admin.sanatorium.localization.localizations.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
