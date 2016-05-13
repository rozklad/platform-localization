
@if ( count($languages) > 0 )
	<?php

			// @todo - make configurable
			$flag_folder = 'sanatorium/localization::flags/16/';

			// @todo - make configurable
			$flag_names = [
				'cs' => 'Czech Republic.png',
				'en' => 'United Kingdom(Great Britain).png',
				'de' => 'Germany.png',
				'fr' => 'France.png',
			];

	?>

	<ul class="{{ $class }}">
		
		<li class="dropdown" class="language language-active language-dropdown-item language-{{ $active_language->locale }}">

			<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown">

					<span class="language-flag">
						<img src="{{ Asset::getUrl($flag_folder.$flag_names[$active_language->locale]) }}">
					</span>

					<span class="language-label">
						{{ $active_language->name }}
					</span>

					<span class="language-locale">
						{{ $active_language->locale }}
					</span>

					<b class="caret"></b>

			</a>

			<ul class="dropdown-menu" role="menu">
				
				@foreach($languages as $language)

					<li class="language-item language-{{ $language->locale }} {{ $language->locale == $active_language->locale ? 'language-active' : '' }}">
						<a href="{{ route('sanatorium.localization.languages.set', $language->locale) }}">
							<span class="language-flag">
								<img src="{{ Asset::getUrl($flag_folder.$flag_names[$language->locale]) }}">
							</span>

							<span class="language-label">
								{{ $language->name }}
							</span>

							<span class="language-locale">
								{{ $language->locale }}
							</span>
						</a>
					</li>

				@endforeach

			</ul>

		</li>
		
	</ul>
@endif