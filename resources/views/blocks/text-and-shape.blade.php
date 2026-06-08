{{--
    Title: Image + CTA (forme organique)
    Category: blocs-Tolle
    SupportsAnchor: true
    Icon: <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="rgb(0,0,0)" d="M320 64C462.5 64 576 177.5 576 320C576 462.5 462.5 576 320 576C177.5 576 64 462.5 64 320C64 177.5 177.5 64 320 64zM320 128C212.5 128 128 212.5 128 320C128 427.5 212.5 512 320 512C427.5 512 512 427.5 512 320C512 212.5 427.5 128 320 128zM224 288L416 288L416 352L224 352L224 288z"/></svg>
--}}

@php
    extract(get_fields());

    $clip_id = 'blob-clip-' . $block['id'];
    $image_tag = '';

    if (!empty($image)) {
        $image_tag = wp_get_attachment_image($image['id'], 'large', false, [
            'class' => 'block w-full h-full object-cover',
            'loading' => 'lazy',
            'alt' => $image['alt'] ?? '',
            'style' => "-webkit-clip-path: url(#{$clip_id}); clip-path: url(#{$clip_id});",
        ]);
    }
@endphp

{{--
    Le SVG est placé avant la section, hors de tout conteneur avec overflow,
    pour garantir que le clipPath soit accessible dans tous les navigateurs.
--}}
@if (!empty($image_tag))
    <svg width="0" height="0" aria-hidden="true" focusable="false" style="position: absolute; overflow: hidden;">
        <defs>
            <clipPath id="{{ $clip_id }}" clipPathUnits="objectBoundingBox">
                <path
                    d="M 0.02156,0.23491 L 0.24080,0.83643 C 0.30273,1.00706 0.44080,1.05165 0.54917,0.93472 L 0.93828,0.51565 C 1.04623,0.39917 1.00899,0.26585 0.85460,0.21762 L 0.18892,0.01196 C 0.03453,-0.03582 -0.03994,0.06383 0.02156,0.23491 Z" />
            </clipPath>
        </defs>
    </svg>

    {{-- <svg
        width="0"
        height="0"
        aria-hidden="true"
        focusable="false"
        style="position: absolute; overflow: hidden;"
    >
        <defs>
            <clipPath
              id="{{ $clip_id }}"
              clipPathUnits="objectBoundingBox"
            >
              <path d="M 0.38079,0.06967 L 0.07084,0.33258 C 0.00326,0.39004 -0.01996,0.50428 0.01866,0.58834 L 0.16685,0.90960 C 0.20546,0.99366 0.30252,1.02742 0.37505,0.97488 C 0.61117,0.80319 0.83946,0.52306 0.98478,0.24092 C 1.02418,0.16447 0.97200,0.08041 0.87573,0.05693 L 0.67718,0.00842 C 0.58064,-0.01506 0.44784,0.01222 0.38053,0.06967 H 0.38079 Z"/>
            </clipPath>
        </defs>
    </svg> --}}
@endif


<section @if (!empty($block['anchor'])) id="{{ $block['anchor'] }}" @endif data-block-id="{{ $block['id'] }}"
    class="bg-green-900 block-{{ $block['classes'] }}">
    <div class="wrapper --mobile-full">
        <div class="padd">
            <div class="wrap">
                <div class="flex flex-col md:flex-row items-center justify-center gap-10 md:gap-16 lg:gap-24 py-8 md:py-16">
                    {{-- IMAGE --}}
                    @if (!empty($image_tag))
                        <div class="shrink-0 w-72 md:w-80 lg:w-96 xl:w-[480px] h-full aspect-[620/572]">
                            {!! $image_tag !!}
                        </div>
                    @endif

                    {{-- CONTENU --}}
                    <div class="flex flex-col items-start gap-8">
                        @if (!empty($title))
                            <h2 class="text-36 md:text-48 lg:text-60 font-extrabold text-white leading-tight mb-0!">
                                {!! nl2br(e($title)) !!}
                            </h2>
                        @endif

                        @if (!empty($text))
                            <div class="text-white text-18 md:text-20">
                                {!! $text !!}
                            </div>
                        @endif

                        @include('components.buttons')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
