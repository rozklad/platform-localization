<?php namespace Sanatorium\Localization\Repositories\Translation;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;
use Platform\Operations\Repositories\ExtensionRepositoryInterface;

class TranslationRepository implements TranslationRepositoryInterface
{

    use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

    /**
     * The Data handler.
     *
     * @var \Sanatorium\Localization\Handlers\Translation\TranslationDataHandlerInterface
     */
    protected $data;

    /**
     * The Eloquent localization model.
     *
     * @var string
     */
    protected $model;

    protected $extensions;

    /**
     * Constructor.
     *
     * @param  \Illuminate\Container\Container $app
     * @return void
     */
    public function __construct(Container $app, ExtensionRepositoryInterface $extensions)
    {
        $this->setContainer($app);

        $this->setDispatcher($app['events']);

        $this->data = $app['sanatorium.localization.translation.handler.data'];

        $this->setValidator($app['sanatorium.localization.translation.validator']);

        $this->setModel(get_class($app['Sanatorium\Localization\Models\Translation']));

        $this->extensions = $extensions;
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
        return $this->container['cache']->rememberForever('sanatorium.localization.translation.all', function ()
        {
            return $this->createModel()->get();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->container['cache']->rememberForever('sanatorium.localization.translation.' . $id, function () use ($id)
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
        return !$id ? $this->create($input) : $this->update($id, $input);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $input)
    {
        // Create a new translation
        $translation = $this->createModel();

        // Fire the 'sanatorium.localization.translation.creating' event
        if ( $this->fireEvent('sanatorium.localization.translation.creating', [$input]) === false )
        {
            return false;
        }

        // Prepare the submitted data
        $data = $this->data->prepare($input);

        // Validate the submitted data
        $messages = $this->validForCreation($data);

        // Check if the validation returned any errors
        if ( $messages->isEmpty() )
        {
            // Save the translation
            $translation->fill($data)->save();

            // Fire the 'sanatorium.localization.translation.created' event
            $this->fireEvent('sanatorium.localization.translation.created', [$translation]);
        }

        return [$messages, $translation];
    }

    /**
     * {@inheritDoc}
     */
    public function update($id, array $input)
    {
        // Get the translation object
        $translation = $this->find($id);

        // Fire the 'sanatorium.localization.translation.updating' event
        if ( $this->fireEvent('sanatorium.localization.translation.updating', [$translation, $input]) === false )
        {
            return false;
        }

        // Prepare the submitted data
        $data = $this->data->prepare($input);

        // Validate the submitted data
        $messages = $this->validForUpdate($translation, $data);

        // Check if the validation returned any errors
        if ( $messages->isEmpty() )
        {
            // Update the translation
            $translation->fill($data)->save();

            // Fire the 'sanatorium.localization.translation.updated' event
            $this->fireEvent('sanatorium.localization.translation.updated', [$translation]);
        }

        return [$messages, $translation];
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id)
    {
        // Check if the translation exists
        if ( $translation = $this->find($id) )
        {
            // Fire the 'sanatorium.localization.translation.deleting' event
            $this->fireEvent('sanatorium.localization.translation.deleting', [$translation]);

            // Delete the translation entry
            $translation->delete();

            // Fire the 'sanatorium.localization.translation.deleted' event
            $this->fireEvent('sanatorium.localization.translation.deleted', [$translation]);

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

        return $this->update($id, ['enabled' => true]);
    }

    /**
     * {@inheritDoc}
     */
    public function disable($id)
    {
        $this->validator->bypass();

        return $this->update($id, ['enabled' => false]);
    }

    /**
     * Returns all locales currently available.
     * 
     * @return mixed
     */
    public function getLocales()
    {
        return $this->createModel()
            ->whereNotNull('value')
            ->groupBy('locale')
            ->lists('locale');
    }

    /**
     * Returns all namespaces registered with their
     * languages to Translation.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->createModel()
            ->whereNotNull('value')
            ->groupBy('namespace')
            ->lists('namespace');
    }

    /**
     * Returns all namespaces registered with their
     * languages to Translation within namespace.
     *
     * @return array
     */
    public function getGroups($namespace = null)
    {
        return $this->createModel()
            ->where('namespace', $namespace)
            ->whereNotNull('value')
            ->groupBy('group')
            ->lists('group');
    }

    /**
     * Returns all namespaces registered with their
     * languages to Translation and adds labels
     * to use (for example in admin interface).
     *
     * @return array
     */
    public function getNamespacesWithLabels()
    {
        $result = [];

        foreach ( $this->getNamespaces() as $namespace )
        {
            if ( $extension = $this->extensions->find($namespace) )
            {
                $result[ $namespace ] = $extension->name;
            } else
            {
                $result[ $namespace ] = $this->getNamespaceLabel($namespace);
            }
        }

        return $result;
    }

    /**
     * Pass language namespace and get human readable
     * label if possible.
     *
     * @param null $namespace
     * @return string
     */
    public function getNamespaceLabel($namespace = null)
    {

        switch ( $namespace )
        {
            case '':
                return 'App language';
                break;

            case
                'platform/access':
			case 'platform/menus':
			case 'platform/operations':
			case 'platform/settings':
				return ucfirst(str_replace('platform/', '', $namespace));
				break;

			default:
				return 'Unknown';
				break;
        }

    }

}
