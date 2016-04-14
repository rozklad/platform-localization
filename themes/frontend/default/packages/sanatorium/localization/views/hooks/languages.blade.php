
@if ( count($languages) > 0 )
	<ul class="{{ $class }}">
		
		<li class="dropdown">

			<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown">
				
					{{ strtoupper($active_language->name) }}
					
					<b class="caret"></b>

			</a>

			<ul class="dropdown-menu" role="menu">
				
				@foreach($languages as $language)

					<li><a href="{{ route('sanatorium.localization.languages.set', $language->locale) }}">{{ strtoupper($language->name) }}</a></li>

				@endforeach

			</ul>

		</li>
		
	</ul>
@endif