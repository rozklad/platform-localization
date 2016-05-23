<?php namespace Sanatorium\Localization\Controllers\Admin;

/**
 * StringsController
 *
 * @credits barryvdh - The initial version was based on the great package "barryvdh/laravel-translation-manager"
 */

use Platform\Access\Controllers\AdminController;
use Platform\Operations\Repositories\ExtensionRepositoryInterface;
use Sanatorium\Localization\Repositories\Translation\TranslationRepositoryInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Symfony\Component\Finder\Finder;
use Lang;
use Sanatorium\Localization\Models\Translation;

class StringsController extends AdminController
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
     * @var Application
     */
    protected $app;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * Constructor.
     *
     * @param ExtensionRepositoryInterface   $extensions
     * @param TranslationRepositoryInterface $translations
     * @param Application                    $app
     * @param Filesystem                     $files
     * @param Dispatcher                     $events
     * @return void
     */
    public function __construct(
        ExtensionRepositoryInterface $extensions,
        TranslationRepositoryInterface $translations,
        Application $app,
        Filesystem $files,
        Dispatcher $events
    )
    {
        parent::__construct();

        $this->extensions = $extensions;
        $this->translations = $translations;
        $this->app = $app;
        $this->files = $files;
        $this->events = $events;
    }

    /**
     * Just testing around
     * @return mixed
     */
    public function index()
    {
        return $this->translations->getNamespacesWithLabels();
    }

    /**
     * Return all lang folders in app.
     * Supports:
     *  /extensions
     *  /resources
     *  /workbench
     *
     * @todo: add support for /vendor
     * @return array
     */
    public function getLangFolders()
    {
        // Get lang path for app lang
        $langPaths = [
            '' => $this->app->langPath(),
        ];

        // Get lang paths for all extensions lang
        foreach ( $this->extensions->findAll() as $extension )
        {
            $langPath = $extension->getPath() . '/lang';
            if ( !$this->files->exists($langPath) )
                continue;

            $langPaths[ $extension->getSlug() ] = $langPath;
        }

        return $langPaths;
    }

    /**
     * Clean up the current records and load all.
     */
    public function load()
    {
        $this->truncate();

        $this->saveAllStrings();

        $this->alerts->success(trans('sanatorium/translation::translations/message.success.load'));

        // @todo: meaningful ajax response
        if ( request()->ajax() )
            return [];

        return redirect()->back();
    }

    public function export()
    {
        $namespace = request()->has('namespace') ? request()->get('namespace') : '*';

        $group = request()->has('group') ? request()->get('group') : '*';

        $this->exportTranslations($namespace, $group);

        $this->alerts->success(trans('sanatorium/translation::translations/message.success.export', compact('namespace', 'group')));

        // @todo: meaningful ajax response
        if ( request()->ajax() )
            return [];

        return redirect()->back();
    }

    public function clean()
    {
        Translation::whereNull('value')->delete();
    }

    public function truncate()
    {
        Translation::truncate();
    }

    /**
     * Load all strings from language files and save them
     * to database tables.
     *
     * @return array|\Illuminate\Http\RedirectRespons
     */
    public function saveAllStrings()
    {
        foreach( $this->getAllStrings() as $locale => $namespaces )
        {
            foreach( $namespaces as $namespace => $groups )
            {
                foreach ( $groups as $group => $translations )
                {
                    if ( $translations && is_array($translations) )
                    {
                        foreach ( array_dot($translations) as $key => $value )
                        {
                            $value = (string) $value;
                            $translation = Translation::firstOrNew([
                                'locale'    => $locale,
                                'group'     => $group,
                                'key'       => $key,
                                'namespace' => $namespace,
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
                        }
                    }
                }
            }
        }

        if ( request()->ajax() )
        {
            return [
                'success' => true
            ];
        }

        return redirect()->back();
    }

    public function getAllStrings()
    {
        $output = [];

        $folders = $this->getLangFolders();

        foreach( $folders as $namespace => $folder )
        {
            foreach ( $this->files->directories($folder) as $localePath )
            {
                $locale = basename($localePath);

                if ( !isset($output[$locale]) )
                    $output[$locale] = [];

                $output[$locale][$namespace] = $this->getStringsFrom($localePath, $locale, $namespace);
            }
        }

        return $output;

    }

    public function getStringsFrom($localePath, $locale, $namespace = null)
    {
        $output = [];

        foreach ( $this->files->allFiles($localePath) as $file )
        {
            $group = str_replace([$localePath.'/', '.php'], '', $file);

            // Namespace is nullable column
            if ( empty($namespace) )
                $namespace = null;

            $translations = Lang::getLoader()->load($locale, $group, $namespace);

            $output[$group] = $translations;

        }

        return $output;
    }

    /**
     * Exports all translations in given namespace and group to files.
     *
     * @param null $namespace   Namespace of language (vendor/package|*)
     * @param null $group       Group of language (group/file|file|*)
     * @param bool $backup      Backup the original files (on by default, suppress only if 100% sure)
     * @param bool $preview     Choose preview to not perform any operations, just show how the exported would look
     * @return array
     */
    public function exportTranslations($namespace = null, $group = null, $backup = true, $preview = false)
    {
        if ( $namespace == '*' )
            return $this->exportTranslationsAll($backup);

        if ( $group == '*' )
            return $this->exportTranslationsNamespace($namespace, $backup);

        $tree = $this->makeTree(
            Translation::where('group', $group)
                ->where('namespace', $namespace)
                ->whereNotNull('value')
                ->get()
        );

        // Preview (dry run)
        if ( $preview )
            return $tree;

        foreach ( $tree as $locale => $namespaces )
        {
            foreach( $namespaces as $namespace => $groups )
            {
                if ( isset($groups[ $group ]) )
                {
                    $translations = $groups[ $group ];

                    if ( empty($namespace) ) {
                        $langPath = $this->app->langPath();
                    } else {
                        $langPath = $this->extensions->find($namespace)->getPath() . '/lang';
                    }

                    $path =  $langPath . '/' . $locale . '/' . $group . '.php';

                    // Make a backup if something went wrong
                    if ( $backup )
                        $this->makeBackup($path, $namespace, $locale, $group);

                    $output = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

                    $this->writeLangFile($path, $output);
                }
            }
        }

        Translation::where('group', $group)
            ->where('namespace', $namespace)
            ->whereNotNull('value')
            ->update(['status' => Translation::STATUS_SAVED]);

        return $tree;
    }

    /**
     * Exports all translations in given namespace to files.
     *
     * @param null $namespace
     * @param bool $backup
     * @return array
     */
    protected function exportTranslationsNamespace($namespace = null, $backup = true)
    {
        $results = [];

        foreach ( $this->translations->getGroups($namespace) as $group ) {

            $results[] = $this->exportTranslations($namespace, $group, $backup);

        }

        return $results;
    }

    /**
     * Exports all translations to files.
     *
     * @param bool $backup
     * @return array
     */
    protected function exportTranslationsAll($backup = true)
    {
        $results = [];

        foreach ( $this->translations->getNamespaces() as $namespace ) {

            $results[] = $this->exportTranslationsNamespace($namespace, $backup);

        }

        return $results;
    }

    /**
     * Returns tree formatted array from given translations.
     *
     * @param $translations
     * @return array
     */
    protected function makeTree($translations)
    {
        $array = [];
        foreach ( $translations as $translation )
        {
            array_set($array[ $translation->locale ][ $translation->namespace ][ $translation->group ], $translation->key, $translation->value);
        }

        return $array;
    }

    /**
     * @param $path     Path to file
     * @param $output   File contents
     */
    protected function writeLangFile($path, $output)
    {
        $directory = dirname($path);

        if ( !$this->files->exists( $directory ) ) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $this->files->put($path, $output);
    }

    /**
     * Makes a backup file in sanatorium/localization
     * backups folder.
     *
     * @param null $path
     * @param null $namespace
     * @param null $locale
     * @param null $group
     * @return bool
     */
    protected function makeBackup($path = null, $namespace = null, $locale = null, $group = null)
    {
        if ( ! $this->files->exists($path) )
            return false;

        $backup_path = $this->getBackupPath($namespace, $locale, $group);
        $backup_directory = dirname($backup_path);

        if ( !$this->files->exists( $backup_directory ) ) {
            $this->files->makeDirectory($backup_directory, 0755, true);
        }

        return $this->files->copy($path, $backup_path);
    }

    /**
     * Returns path to backup file derived from namespace, locale, group.
     *
     * @param null $namespace
     * @param null $locale
     * @param null $group
     * @return string
     */
    protected function getBackupPath($namespace = null, $locale = null, $group = null)
    {
        return __DIR__ . '/../../../backups/' . $namespace . '/' . $locale . '/' . $group . '_' . date('YmdHis') . '.php';
    }
}
