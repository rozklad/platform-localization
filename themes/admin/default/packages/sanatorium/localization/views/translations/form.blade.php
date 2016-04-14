@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/localization::translations/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="localization-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.localization.translations.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $translations->exists ? $translations->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($translations->exists)
							<li>
								<a href="{{ route('admin.sanatorium.localization.translations.delete', $translations->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/localization::translations/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/localization::translations/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('namespace', ' has-error') }}">

									<label for="namespace" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/localization::translations/model.general.namespace_help') }}}"></i>
										{{{ trans('sanatorium/localization::translations/model.general.namespace') }}}
									</label>

									<input type="text" class="form-control" name="namespace" id="namespace" placeholder="{{{ trans('sanatorium/localization::translations/model.general.namespace') }}}" value="{{{ input()->old('namespace', $translations->namespace) }}}">

									<span class="help-block">{{{ Alert::onForm('namespace') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('locale', ' has-error') }}">

									<label for="locale" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/localization::translations/model.general.locale_help') }}}"></i>
										{{{ trans('sanatorium/localization::translations/model.general.locale') }}}
									</label>

									<input type="text" class="form-control" name="locale" id="locale" placeholder="{{{ trans('sanatorium/localization::translations/model.general.locale') }}}" value="{{{ input()->old('locale', $translations->locale) }}}">

									<span class="help-block">{{{ Alert::onForm('locale') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('entity_id', ' has-error') }}">

									<label for="entity_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/localization::translations/model.general.entity_id_help') }}}"></i>
										{{{ trans('sanatorium/localization::translations/model.general.entity_id') }}}
									</label>

									<input type="text" class="form-control" name="entity_id" id="entity_id" placeholder="{{{ trans('sanatorium/localization::translations/model.general.entity_id') }}}" value="{{{ input()->old('entity_id', $translations->entity_id) }}}">

									<span class="help-block">{{{ Alert::onForm('entity_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('entity_field', ' has-error') }}">

									<label for="entity_field" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/localization::translations/model.general.entity_field_help') }}}"></i>
										{{{ trans('sanatorium/localization::translations/model.general.entity_field') }}}
									</label>

									<input type="text" class="form-control" name="entity_field" id="entity_field" placeholder="{{{ trans('sanatorium/localization::translations/model.general.entity_field') }}}" value="{{{ input()->old('entity_field', $translations->entity_field) }}}">

									<span class="help-block">{{{ Alert::onForm('entity_field') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('entity_value', ' has-error') }}">

									<label for="entity_value" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/localization::translations/model.general.entity_value_help') }}}"></i>
										{{{ trans('sanatorium/localization::translations/model.general.entity_value') }}}
									</label>

									<textarea class="form-control" name="entity_value" id="entity_value" placeholder="{{{ trans('sanatorium/localization::translations/model.general.entity_value') }}}">{{{ input()->old('entity_value', $translations->entity_value) }}}</textarea>

									<span class="help-block">{{{ Alert::onForm('entity_value') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($translations)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
