
@if ( count($languages) > 0 )

    <ul class="{{ $class }}">

        <li class="dropdown language language-active language-dropdown-item language-{{ $active_language->locale }}">

            <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown">


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
