<?php namespace Sanatorium\Localization\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Translation extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	CONST STATUS_SAVED = 1;
	CONST STATUS_CHANGED = 2;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'translations';

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
	protected static $entityNamespace = 'sanatorium/localization.translation';

}
