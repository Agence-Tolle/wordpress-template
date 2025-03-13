{{--
    Title: Texte et image
    Category: blocs-Tolle
    Icon: admin-comments
--}}

@php
    extract(get_fields())
@endphp

<section 
    @if (!empty($block['anchor'])) id="{{ $block['anchor'] }}" @endif
    data-{{ $block['id'] }} 
    class="bg-white block-{{ $block['classes'] }}"
>
    <div class="container">
        <div class="padd">
            <div class="wrap">
                {{ $title }}
            </div>
        </div>
    </div>
</section>