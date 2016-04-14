<?php namespace Sanatorium\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Localization\Repositories\Language\LanguageRepositoryInterface;

class LanguagesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

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
	 * @param  \Sanatorium\Localization\Repositories\Language\LanguageRepositoryInterface  $languages
	 * @return void
	 */
	public function __construct(LanguageRepositoryInterface $languages)
	{
		parent::__construct();

		$this->languages = $languages;
	}

	/**
	 * Display a listing of language.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/localization::languages.index');
	}

	/**
	 * Datasource for the language Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->languages->grid();

		$columns = [
			'id',
			'locale',
			'name',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.localization.languages.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new language.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new language.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating language.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating language.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified language.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->languages->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/localization::languages/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.localization.languages.all');
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
				$this->languages->{$action}($row);
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
		// Do we have a language identifier?
		if (isset($id))
		{
			if ( ! $language = $this->languages->find($id))
			{
				$this->alerts->error(trans('sanatorium/localization::languages/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.localization.languages.all');
			}
		}
		else
		{
			$language = $this->languages->createModel();
		}

		// Show the page
		return view('sanatorium/localization::languages.form', compact('mode', 'language'));
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
		// Store the language
		list($messages) = $this->languages->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/localization::languages/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.localization.languages.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
