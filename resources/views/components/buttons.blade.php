
@if (! empty($buttonsClone['buttons']))
    <div class="btn-wrapper insight ghost {{ $extraClasses ?? '' }}">
        @foreach ($buttonsClone['buttons'] as $button)
            <a
                href="{{ $button['link']['url'] ?? '' }}"
                target="{{ $button['link']['target'] ?? '_self' }}"
                title="{{ $button['link']['title'] ?? '' }}"
                class="group btn {{ $button['variant'] ? '--' . $button['variant'] : '' }}"
            >
                @if (! empty($button['icon']) && $button['icon_position'] == 'before')
                    <i class="{{ $button['icon'] ?? '' }}"></i>
                @endif
        
                @if (! empty($button['link']['title']))
                    <span class="txt">
                        {{ $button['link']['title'] }}
                    </span>
                @endif

                @if (! empty($button['icon']) && $button['icon_position'] == 'after')
                    <i class="{{ $button['icon'] ?? '' }}"></i>
                @endif
            </a>
        @endforeach
    </div>
@endif