@php
    $buttons = $buttonsclone['buttons'] ?? [];
@endphp

@if ( !empty($buttons) )
    <div class="btn-wrapper insight ghost {{ $extraClasses ?? '' }}">
        @foreach ( $buttons as $button )
            @php
                $link = is_array($button['link'] ?? null) ? $button['link'] : [];
                $url = $link['url'] ?? '';
                $target = $link['target'] ?? '_self';
                $title = $link['title'] ?? '';
                $variant = $button['variant'] ?? '';
                $iconPosition = $button['icon_position'] ?? '';
                $iconField = $button['icon'] ?? null;
                $icon = '';

                if (!empty($iconField['id'])) {
                    $icon_id = (int) $iconField['id'];

                    $icon = wp_get_attachment_image(
                        $icon_id,
                        \App\retina_image_size('icon'),
                        false,
                        [
                            'class' => 'w-[15px] h-[15px]',
                            'loading' => 'lazy',
                            'sizes' => \App\retina_image_sizes('icon'),
                        ]
                    );
                }
            @endphp

            <a
                href="{{ $url }}"
                target="{{ $target }}"
                title="{{ $title }}"
                @if ( $target === '_blank' )
                    rel="noopener noreferrer"
                @endif
                class="group btn {{ $variant ? '--' . $variant : '' }}"
            >
                @if ( !empty($icon) && $iconPosition === 'before' )
                    {!! $icon !!}
                @endif

                @if ( !empty($title) )
                    <span class="txt">
                        {{ $title }}
                    </span>
                @endif

                @if ( !empty($icon) && $iconPosition === 'after' )
                    {!! $icon !!}
                @endif
            </a>
        @endforeach
    </div>
@endif
