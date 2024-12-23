@php
    $schemas = \App\Models\Schema::where('status', true)
        ->with('schedule')
        ->get();
@endphp
<section class="container py-5 mt-5 mb-lg-3 mb-xl-4 mb-xxl-5">
    <div class="text-center pb-3 pt-lg-2 pt-xl-4 my-1 my-sm-3 my-lg-4">
        <h1 class="display-2">{{ $data['title_small'] }}</h1>
        <p class="fs-lg mb-0">{{ $data['title_big'] }}</p>
    </div>
    <div class="row row-cols-3 flex-nowrap pb-4 overflow-auto">
        @foreach ($schemas as $schema)
            <div class="col" style="min-width: 19rem;">
                <div class="card h-100 py-lg-4 @if ($schema->is_trending) border @endif">
                    <div class="card-body w-100 mx-auto text-center" style="max-width: 23rem;">
                        <h3>{{ $schema->name }}</h3>
                        <img src="{{ asset($schema->icon) }}" style="max-width: 100%; max-height: 150px;" alt="" />
                        <div class="display-6 @if ($schema->is_trending) text-white @else text-danger @endif ">
                            {{ $schema->type == 'range' ? $currencySymbol . $schema->min_amount . ' - ' . $currencySymbol . $schema->max_amount : $currencySymbol . $schema->fixed_amount }}
                        </div>
                        <div class="mb-4">
                            @if ($schema->badge)
                                <div class="badge bg-danger">{{ $schema->badge }}</div>
                            @endif
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                {{ $schema->schedule->name . ' ' . __('Aproxidamente') }} <span>{{ $schema->interest_type == 'percentage' ? $schema->return_interest . '%' : $currencySymbol . $schema->return_interest }}</span>
                            </li>
                            <li class="list-group-item">
                                {{ __('Capital Return') }} <span>{{ $schema->capital_back ? __('Yes') : __('No') }}</span>
                            </li>
                            <li class="list-group-item">
                                {{ __('Total Periods') }} <span>{{ ($schema->return_type == 'period' ? $schema->number_of_period . ' ' : __('Unlimited') . ' ') . ($schema->number_of_period == 1 ? __('Time') : __('Times')) }}</span>
                            </li>
                            <li class="list-group-item">
                                {{ __('Cancellation') }} <span>
                                    @if ($schema->schema_cancel)
                                    <i class="fa-solid fa-square-check"></i>{{ __('') . ' ' . $schema->expiry_minute . ' ' . 'Minute' }}
                                    @else
                                    <i class="fa-solid fa-circle-xmark"></i> {{ __('') }}
                                    @endif
                                </span>
                            </li>
                        </ul> 
                        <div class="card-footer">
                            <button class="btn btn-primary w-100" type="button">{{ __('Quero este') }}</button>
                        </div>                                          
                    </div>
                </div>
            </div>
        @endforeach


    </div>
</section>