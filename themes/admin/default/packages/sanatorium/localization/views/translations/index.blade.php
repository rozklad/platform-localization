@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans('sanatorium/localization::translations/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/css/daterangepicker-bs3.css', 'style') }}

{{ Asset::queue('moment', 'moment/js/moment.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('editable', 'sanatorium/localization::bootstrap-editable/bootstrap-editable.js', 'jquery') }}
{{ Asset::queue('editable', 'sanatorium/localization::bootstrap-editable/bootstrap-editable.css') }}


{{-- Inline scripts --}}
@section('scripts')
	<script type="text/javascript">
	function activateEditables() {
		$('.editable').editable().on('hidden', function(event, reason){

			var $row = $(this).closest('tr'),
				raw = $(this).data();

			if(reason === 'save')
			{
				var data = {
					locale: raw.locale,
					key: raw.key,
					group: raw.group,
					namespace: raw.namespace,
					value: raw.editable.value
				};

				$.ajax({
					type: 'POST',
					url: '{{ route('admin.sanatorium.localization.translations.update') }}',
					data: data
				}).success(function(data){

					$row.addClass('success');

				});
			}

			if(reason == 'nochange')
			{

			}

		});
	}

	$(function(){
		$('#namespace').change(function(event){

			var namespace = $(this).val();

			if ( namespace === '-1' )
					return false;

			$.ajax({
				url: '{{ route('admin.sanatorium.localization.translations.namespace') }}',
				data: {namespace: namespace}
			}).success(function(data){

				var args = {
					results: data,
					namespace: namespace
				};

				// Generalte html
				var template        = jQuery('#translation-template').html(),
					html            = _.template(template)(args);

				$('#form-area').html(html);

				activateEditables();

			}).error(function(data){

			});

		});
	});
	</script>
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-grid">

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

					<span class="navbar-brand">{{{ trans('sanatorium/localization::translations/common.title') }}}</span>

				</div>

				<div class="collapse navbar-collapse" id="actions">

					<div class="navbar-form navbar-right">
						<select name="namespace" class="form-control" id="namespace">
							<option value="-1">{{ trans('sanatorium/localization::translations/common.choose_namespace') }}</option>
							@foreach( $namespaces as $namespace => $label )
								<option value="{{ $namespace }}">{{ $label }}</option>
							@endforeach
						</select>
					</div>

					<ul class="nav navbar-nav navbar-left">

						<li class="primary">
							<a href="{{ route('admin.sanatorium.localization.strings.load') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.refresh') }}}">
								<i class="fa fa-refresh"></i> <span class="visible-xs-inline">{{{ trans('action.refresh') }}}</span>
							</a>
						</li>

					</ul>

				</div>

			</div>

		</nav>

	</header>

	<div class="panel-body">


	</div>

	<div class="table-responsive">

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Group</th>
					<th>Key</th>
					@foreach( $locales as $locale )
						<th>{{ $locale }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody id="form-area">
				<tr>
					<td colspan="{{ count($locales) + 2 }}" class="text-center">
						{{ trans('sanatorium/localization::translations/common.choose_namespace') }}
					</td>
				</tr>
			</tbody>
		</table>

	</div>

	<footer class="panel-footer clearfix">

	</footer>

</section>

<script type="text/x-template-lodash" id="translation-template">

	<% _.each( results, function( rows, group ){ %>
		<% _.each( rows, function( r, key ){ %>
			<tr>
				<td width="7%">
					<%= group %>
				</td>
				<td width="13%">
					<%= key %>
				</td>
				@foreach( $locales as $locale )
					<td width="{{ floor(80/count($locales)) }}%">
						<a href="#edit" class="editable"
							data-locale="{{ $locale }}"
							data-key="<%= key %>"
							data-namespace="<%= namespace %>"
							data-group="<%= group %>">
							<% if ( typeof r['{{ $locale }}'] !== 'undefined' ) { %>
								<%= r['{{ $locale }}'] %>
							<% } else { %>
							<% } %>
						</a>
					</td>
				@endforeach
			</tr>
		<% }); %>
	<% }); %>

</script>

@stop
