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
        $request = request();
        $locale = request()->has('locale') ? request()->get('locale') : 'de';
        $created = 0;

        if ( $request->hasFile('po') )
        {

            $path = storage_path('po/import');
            $filename = date('YmdHis') . '.po';
            $filepath = $path . '/' . $filename;

            $request->file('po')->move( $path, $filename );

            $fileHandler = new \Sepia\FileHandler($filepath);

            $poParser = new \Sepia\PoParser($fileHandler);
            $entries  = $poParser->parse();

            foreach( $entries as $key => $entry )
            {
                $entries[$key] = [
                    'tcomment' => str_replace(["\x07", '\\'], ['/a', '/'], $entry['tcomment'][0]),
                    'msgid' => $entry['msgid'][0],
                    'msgstr' => $entry['msgstr'][0]
                ];
            }

            foreach( $entries as $key => $entry )
            {
                // @todo: finding by value is not 100%
                $translation = $this->translations->where('value', $entry['msgid'])->first();

                if ( is_object($translation) && $entry['msgstr'] != '' )
                {
                    $created++;

                    $array = $translation->toArray();
                    $array['locale'] = $locale;
                    $array['value'] = $entry['msgstr'];
                    $array['status'] = \Sanatorium\Localization\Models\Translation::STATUS_CHANGED;
                    unset($array['created_at']);
                    unset($array['updated_at']);
                    unset($array['values']);
                    unset($array['id']);
                    $this->translations->firstOrCreate($array);
                }

            }

            return [
                'created' => $created
            ];

        } else {

            return redirect()->back();

        }

    }


}
