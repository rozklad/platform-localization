<?php namespace Sanatorium\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Platform\Operations\Repositories\ExtensionRepositoryInterface;
use Sanatorium\Localization\Repositories\Translation\TranslationRepositoryInterface;

class PoController extends AdminController
{

    /**
     * @var ExtensionRepositoryInterface
     */
    protected $extensions;

    /**
     * @var TranslationRepositoryInterface
     */
    protected $translations;

    /**
     * Constructor.
     *
     * @param ExtensionRepositoryInterface   $extensions
     * @param TranslationRepositoryInterface $translations
     * @return void
     */
    public function __construct(
        ExtensionRepositoryInterface $extensions,
        TranslationRepositoryInterface $translations
    )
    {
        parent::__construct();

        $this->extensions = $extensions;
        $this->translations = $translations;

    }

    public function export()
    {
        // @todo: rewrite
    }

    public function import()
    {
        return view('sanatorium/localization::po/import');
    }

    public function processImport()
    {
        dd('some');
    }


}
