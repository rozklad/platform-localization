<?php namespace Sanatorium\Localization\Repositories\Localization;


class LocalizedRepository implements LocalizedRepositoryInterface {

    /**
     * Array of registered entities.
     *
     * @var array
     */
    protected $localized;

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->localized;
    }

    /**
     * {@inheritDoc}
     */
    public function localize($localized)
    {
        $this->localized[] = $localized;
    }

}
