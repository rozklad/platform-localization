<?php namespace Sanatorium\Localization\Models;
/**
 * @todo: delete
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Translation extends Model {

	/**
	 * {@inheritDoc}
	 */
	public $table = 'translations';

	/**
	 * {@inheritDoc}
	 */
	public $timestamps = false;

	/**
	 * Locale scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $locale
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeLocale(Builder $query, $locale)
	{
		return $query->whereLocale($locale);
	}

	/**
	 * Field scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $field
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeField(Builder $query, $field)
	{
		return $query->whereEntityField($field);
	}

	/**
	 * Namespace scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $namespace
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeNamespace(Builder $query, $namespace)
	{
		return $query->whereNamespace($namespace);
	}

	/**
	 * Entity scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $id
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeEntity(Builder $query, $id)
	{
		return $query->whereEntityId($id);
	}

}