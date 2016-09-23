<?php namespace Sanatorium\Localization\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Localization extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'localizations';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/localization.localization';

    /**
     * Get mutator for the "entity_value" attribute.
     *
     * @param  string  $entity_value
     * @return array
     */
    public function getEntityValueAttribute($entity_value)
    {
        return strpos($entity_value, 'JSON_ENCODED:') === 0 ? json_decode(str_replace('JSON_ENCODED:', '', $entity_value), true) : $entity_value;
    }

    /**
     * Set mutator for the "entity_value" attribute.
     *
     * @param  array  $entity_value
     * @return void
     */
    public function setEntityValueAttribute($entity_value)
    {
        $this->attributes['entity_value'] = is_array($entity_value) ? 'JSON_ENCODED:' . json_encode($entity_value) : $entity_value;
    }

}
