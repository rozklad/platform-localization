<?php namespace Sanatorium\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Localization\Repositories\Translation\TranslationRepositoryInterface;
use Sanatorium\Localization\Models\Translation;

class TranslationsController extends AdminController {

	/**
	 * The Localization repository.
	 *
	 * @var \Sanatorium\Localization\Repositories\Translation\TranslationRepositoryInterface
	 */
	protected $translations;

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Localization\Repositories\Translation\TranslationRepositoryInterface  $translations
	 * @return void
	 */
	public function __construct(TranslationRepositoryInterface $translations)
	{
		parent::__construct();

		$this->translations = $translations;
	}

	/**
	 * Display a listing of translation.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$namespaces = $this->translations->getNamespacesWithLabels();

		$locales = $this->translations->getLocales();

		return view('sanatorium/localization::translations.index', compact('namespaces', 'locales'));
	}

	public function getnamespace()
	{
		$namespace = request()->get('namespace');

		$tree = request()->has('tree') ? true : false;

		$translations = $this->translations->where('namespace', $namespace)->get();

		return ( $tree ? $translations : $this->flatten($translations) );
	}

	/**
	 * "flatten" translations to better use with x-editable
	 * @param $translations
	 */
	public function flatten($inputTranslations)
	{
		$translations = [];

		foreach( $inputTranslations as $translation )
		{
			$translations[$translation->group][$translation->key][$translation->locale] = $translation->value;
		}

		return $translations;
	}

	public function update()
	{
		$data = request()->all();

		$value = (string) $data['value'];
		$translation = $this->translations->createModel()->firstOrNew([
			'locale'    => $data['locale'],
			'group'     => $data['group'],
			'key'       => $data['key'],
			'namespace' => $data['namespace'],
		]);

		// Check if the database is different then the files
		$newStatus = $translation->value === $value ? Translation::STATUS_SAVED : Translation::STATUS_CHANGED;
		if ( $newStatus !== (int) $translation->status )
		{
			$translation->status = $newStatus;
		}

		if ( !$translation->value )
		{
			$translation->value = $value;
		}

		$translation->save();

		return $translation;

	}

	public function entities()
    {

    }

    public function entitiesUpdate()
    {

    }

}
