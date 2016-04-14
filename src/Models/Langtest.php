<?php namespace Sanatorium\Localization\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Sanatorium\Localization\Traits\LocalizedEntityTrait;

class Langtest extends Model implements EntityInterface {

	use LocalizedEntityTrait;
	// , NamespacedEntityTrait

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'langtests';

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
	protected static $entityNamespace = 'sanatorium/localization.langtest';

}
