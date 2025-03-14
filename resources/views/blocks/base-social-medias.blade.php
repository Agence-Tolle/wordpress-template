{{--
    Title: Social Medias
    Category: blocs-Tolle
    Icon: 
--}}

@php
    extract(get_fields());
@endphp

<section @if (!empty(block['anchor'])) id="{{ block['anchor'] }}" @endif data-{{ $block['id'] }} class="bg-white block-{{ $block['classes'] }}">
    <div class="container">
        <div class="padd">
            <div class="wrap">

            </div>
        </div>
    </div>
</section>