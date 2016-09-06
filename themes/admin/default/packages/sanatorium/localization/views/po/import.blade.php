@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @parent
    {{ trans('sanatorium/localization::po/common.title') }}
@stop

{{-- Queue assets --}}

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

    {{-- Grid --}}
    <section class="panel panel-default panel-grid">

        {{-- Grid: Header --}}
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

                        <span class="navbar-brand">{{{ trans('sanatorium/localization::po/common.title') }}}</span>

                    </div>

                </div>

            </nav>

        </header>

        <div class="panel-body">

            <div class="form-group">

                <label for="po" class="control-label">
                    {{{ trans('sanatorium/localization::po/common.label') }}}
                </label>

                <input type="file" id="po" name="po">

            </div>

        </div>

    </section>


@stop
