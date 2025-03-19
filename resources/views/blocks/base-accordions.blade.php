{{--
    Title: Accordéons
    Category: blocs-Tolle
    Icon: <svg viewBox="0 0 448 512"><path class="fa-secondary" opacity=".4" d="M39 332c-11 13.8-8.8 33.9 5 45L204 505c5.8 4.7 12.9 7 20 7s14.1-2.3 20-7L404 377c13.8-11 16-31.2 5-45s-31.2-16-45-5L224 439 84 327c-5.9-4.7-13-7-20-7c-9.4 0-18.7 4.1-25 12z"/><path class="fa-primary" d="M204 7c11.7-9.3 28.3-9.3 40 0L404 135c13.8 11 16 31.2 5 45s-31.2 16-45 5L224 73 84 185c-13.8 11-33.9 8.8-45-5s-8.8-33.9 5-45L204 7z"/></svg>
--}}

@php
    extract(get_fields());
@endphp

<section @if (!empty($block['anchor'])) id="{{ $block['anchor'] }}" @endif data-{{ $block['id'] }} class="bg-white block-{{ $block['classes'] }}">
    <div class="container">
        <div class="padd">
            <div class="wrap">
                {{-- TITLE --}}
                @if (! empty($title))
                    <h2 class="font-semibold leading-tight insight ghost delay--2">
                        {{ $title }}
                    </h2>
                @endif

                @if (! empty($accordions))
                    @foreach ($accordions as $accordion)
                        <div class="accordion insight ghost delay--2">
                            <div class="accordion-header">
                                <p class="text-lg md:text-xl lg:text-2xl font-semibold text-primary">
                                    {{ $accordion['title'] }}
                                </p>
                                <span class="plusMinus">
                                    <span></span>
                                    <span></span>
                                </span>
                            </div>

                            <div class="accordion-body">
                                <div class="pb-8">
                                    <div class="generic-content">
                                        {!! $accordion['content'] !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>