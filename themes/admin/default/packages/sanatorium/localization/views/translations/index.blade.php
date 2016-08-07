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

		}).on('save', function(){
			// Automatically jump to next editable on save
			var that = this,
				index = $(that).parents('td:first').index();
			setTimeout(function() {
				$(that).closest('tr').next().find('td:nth-child('+(index + 1)+')').find('.editable').editable('show');
			}, 200);
		});
	}

	$(function(){
		$('#namespace').change(function(event){

			var namespace = $(this).val();

			if ( namespace === '*' )
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

		$('[data-actionable]').click(function(event){

			event.preventDefault();

			var url = $(this).attr('href'),
				namespace = $('#namespace').val();

			$.ajax({
				type: 'GET',
				url: url,
				data: {namespace: namespace}
			}).success(function(data){

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
							<option value="*">{{ trans('sanatorium/localization::translations/common.choose_namespace') }}</option>
							@foreach( $namespaces as $namespace => $label )
								<option value="{{ $namespace }}">{{ $label }}</option>
							@endforeach
						</select>
					</div>

					<ul class="nav navbar-nav navbar-left">

						<li>
							<a href="{{ route('admin.sanatorium.localization.strings.load') }}" data-toggle="tooltip" data-original-title="{{{ trans('common.refresh') }}}" data-actionable>
								<i class="fa fa-refresh"></i> <span class="visible-xs-inline">{{{ trans('common.refresh') }}}</span>
							</a>
						</li>

						<li>
							<a href="{{ route('admin.sanatorium.localization.strings.export') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}" data-actionable>
								<i class="fa fa-pencil"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
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
							data-group="<%= group %>"
							data-type="textarea"
							data-title="Enter translation"
							data-mode="inline"
							><% if ( typeof r['{{ $locale }}'] !== 'undefined' ) { %><%= r['{{ $locale }}'] %><% } else { %><% } %></a>
					</td>
				@endforeach
			</tr>
		<% }); %>
	<% }); %>

</script>

@stop
