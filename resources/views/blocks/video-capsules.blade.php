{{--
    Title: Capsules vidéo
    Category: blocs-Tolle
    Icon: <svg viewBox="0 0 512 512"><path d="M0 64C0 28.7 28.7 0 64 0L448 0c35.3 0 64 28.7 64 64l0 288c0 35.3-28.7 64-64 64l-138.7 0L185.6 508.8c-4.8 3.6-11.3 4.2-16.8 1.5s-8.8-8.2-8.8-14.3l0-80-96 0c-35.3 0-64-28.7-64-64L0 64zm160 48c-17.7 0-32 14.3-32 32l0 48c0 17.7 14.3 32 32 32l32 0 0 7.3c0 11.7-8.5 21.7-20.1 23.7l-7.9 1.3c-13.1 2.2-21.9 14.5-19.7 27.6s14.5 21.9 27.6 19.7l7.9-1.3c34.7-5.8 60.2-35.8 60.2-71l0-39.3 0-24 0-24c0-17.7-14.3-32-32-32l-48 0zm224 80l0-24 0-24c0-17.7-14.3-32-32-32l-48 0c-17.7 0-32 14.3-32 32l0 48c0 17.7 14.3 32 32 32l32 0 0 7.3c0 11.7-8.5 21.7-20.1 23.7l-7.9 1.3c-13.1 2.2-21.9 14.5-19.7 27.6s14.5 21.9 27.6 19.7l7.9-1.3c34.7-5.8 60.2-35.8 60.2-71l0-39.3z"/></svg>
--}}
@php
extract(get_fields());
$capsules = $capsules ?? [];
@endphp

@if (!empty($capsules))
<section class="video-capsules py-16 md:py-24">
    <div class="wrapper">

        @if (!empty($eyebrow) || !empty($title))
        <div class="mb-10 md:mb-14 text-center">
            @if (!empty($eyebrow))
            <p class="eyebrow">
                {{ $eyebrow }}
            </p>
            @endif
            @if (!empty($title))
            <h2>
                {!! $title !!}
            </h2>
            @endif
        </div>
        @endif

        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6"
            data-video-grid
        >
            @foreach ($capsules as $index => $capsule)
            @php
                $video_url = $capsule['video_url'] ?? '';
                $poster    = $capsule['poster'] ?? null;
                $poster_url = $poster ? $poster['url'] : '';
                $caption   = $capsule['caption'] ?? '';
            @endphp
            <article
                class="video-capsule group relative bg-white rounded-2xl overflow-hidden cursor-pointer"
                data-capsule-index="{{ $index }}"
                data-video-url="{{ $video_url }}"
                aria-label="{{ $caption }}"
            >
                <div class="relative aspect-[3/4] overflow-hidden">
                    {{-- Poster (thumbnail statique) --}}
                    <img
                        src="{{ $poster_url }}"
                        alt="{{ $caption }}"
                        loading="lazy"
                        decoding="async"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        data-poster
                    >

                    {{-- Vidéo (chargée au hover) --}}
                    <video
                        class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        muted
                        loop
                        playsinline
                        preload="none"
                        aria-hidden="true"
                        data-video-src="{{ $video_url }}"
                        data-hover-video
                    ></video>
                </div>

                <div class="flex items-center justify-between gap-3 p-4 md:p-5">
                    @if (!empty($caption))
                    <p class="text-sm md:text-base font-medium leading-snug">
                        {{ $caption }}
                    </p>
                    @endif

                    <button
                        type="button"
                        class="shrink-0 flex items-center justify-center w-11 h-11 rounded-full border border-gray-300 hover:border-gray-800 transition-colors duration-200"
                        data-lightbox-trigger="{{ $index }}"
                        aria-label="Lire la vidéo : {{ $caption }}"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-4 h-4 translate-x-px"
                            aria-hidden="true"
                        >
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </button>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>

{{-- Lightbox --}}
<div
    class="video-lightbox fixed inset-0 z-50 flex items-center justify-center bg-black/90 opacity-0 pointer-events-none transition-opacity duration-300"
    role="dialog"
    aria-modal="true"
    aria-label="Lecteur vidéo"
    data-lightbox
    hidden
>
    <div class="relative w-full max-w-2xl mx-4 my-4">
        <video
            class="w-full rounded-xl aspect-[3/4] bg-black"
            controls
            playsinline
            preload="metadata"
            data-lightbox-video
        ></video>
    </div>

    {{-- Navigation --}}
    <button
        type="button"
        class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors duration-200 disabled:opacity-30 disabled:pointer-events-none"
        data-lightbox-prev
        aria-label="Vidéo précédente"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true">
            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
        </svg>
    </button>

    <button
        type="button"
        class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors duration-200 disabled:opacity-30 disabled:pointer-events-none"
        data-lightbox-next
        aria-label="Vidéo suivante"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true">
            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
        </svg>
    </button>

    <button
        type="button"
        class="absolute top-4 right-4 flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors duration-200"
        data-lightbox-close
        aria-label="Fermer"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5" aria-hidden="true">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
    </button>
</div>
@endif
