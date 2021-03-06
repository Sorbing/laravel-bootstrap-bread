@extends($layout)

@section('content')
    <?php $breadOldCheckedIds = array_wrap(request()->old('bread_ids')); ?>

    <div class="container-fluid container-bread container-bread-browse">
        <div class="row">
            <div class="col-sm-12 mx-auto">
                <div class="row">
                    <div class="col-sm-8">
                        <h1 class="mb-0 mt-2">
                            {{ $title or ucwords(str_replace('.', ' ', $prefix)) }}
                            ({{ $paginator->total() }})
                        </h1>
                    </div>
                    <div class="col-sm-4 text-right">
                        <a href="{{ route("$prefix.create") }}" class="btn btn-primary">{{ __('Create') }}</a>

                        @push('bread_assets')
                            {{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">--}}
                            {{-- 6.8Kb --}}
                            {{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>--}}

                            <style>
                                /* CSS */
                                /*.container-bread-browse .dropdown-toggle:focus ~ .dropdown-menu { display: block; }
                                .container-bread-browse .dropdown .dropdown-menu:hover { display: block; }*/

                                /* JS */
                                .container-bread-browse .dropdown-toggle.opened ~ .dropdown-menu { display: block; }

                                .container-bread-browse .dropdown .dropdown-menu { padding: 0.15rem 0; }
                                .container-bread-browse .dropdown .dropdown-menu .dropdown-item { padding: 0.15rem 0.3rem; }
                                .container-bread-browse .dropdown .dropdown-menu .dropdown-item > * { display: block; width: 100%; }
                            </style>

                            <script>
                                // document.querySelector('.container-bread')
                                document.addEventListener('click', function(e) {
                                    let dropdown = e.target.closest('.breadDropdown');
                                    let isInside = !!dropdown;

                                    if (isInside) {
                                        const toggle = e.target.closest('.dropdown-toggle'); //document.querySelector('#breadCustomActionsToggler466').closest('.dropdown-toggle')
                                        const isClickOnToggle = !!toggle;
                                        const isOpened = (toggle && toggle.classList.contains('opened'));
                                        if (isClickOnToggle) {
                                            isOpened ? toggle.classList.remove('opened') : toggle.classList.add('opened');
                                        }
                                    } else {
                                        document.querySelectorAll('.breadDropdown .dropdown-toggle').forEach(el => {
                                            el.classList.remove('opened');
                                        });
                                    }
                                });
                            </script>
                        @endpush

                        <div class="dropdown float-md-right breadDropdown breadMassActionsWrap"> {{-- tabindex="0" --}}
                            <button class="btn btn-primary dropdown-toggle" id="breadMassActionsToggler" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Mass actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="breadMassActionsToggler" style="right: 5px; left: auto;">
                                @foreach($mass_actions as $action)
                                    <div class="dropdown-item">
                                        @if(is_array($action))
                                            @include('bread::parts.mass_action_form', $action)
                                        @elseif(is_string($action))
                                            {!! app('bread')->renderBlade($action) !!}
                                        @endif
                                    </div>
                                @endforeach
                                {{--<div class="dropdown-item">
                                    @include('bread::parts.mass_action_form', ['name' => 'Delete', 'action' => route("$prefix.destroy", 0)])
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($preset_filters) && count($preset_filters))
                    <div class="row bread-preset-filters-wrap">
                        <div class="col-sm-12">
                            @foreach($preset_filters as $name => $preset_filter)
                                <?php
                                    $preset_query = rtrim('?' . data_get($preset_filter, 'query'), '?');
                                    $preset_url = route("$prefix.index") . $preset_query;
                                ?>
                                {{--<a href="{{ $preset_url }}" class="badge badge-secondary">{{ $name }}</a>--}}
                                <a href="{{ $preset_url }}">
                                    @if ($preset_query && mb_stripos(urldecode(request()->fullUrl()), $preset_query))
                                        <b>{{ $name }}</b>
                                    @else
                                        {{ $name }}
                                    @endif
                                </a>

                                @if (!$loop->last) <span style="margin: 0 5px; color: #999;">|</span> @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @include('bread::table', [
                    'paginator' => $paginator,
                    'columns' => $columns,
                    'columns_settings' => $columns_settings,
                    'prefix' => $prefix
                ])

                @if ($paginator->total())
                    {!! urldecode($paginator->appends(request()->all())->links()) !!}
                    {{-- @note That used a urlencode, ex. `,` to `%2C`  --}}
                    {{-- $paginator->appends(request()->all())->links() --}}
                @else
                    {{ $empty_content or '' }}
                @endif
            </div>
        </div>
    </div>
@endsection