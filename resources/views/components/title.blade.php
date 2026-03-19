@if ( !empty($titlegroup['title']) )
    <{{ $titlegroup['tag'] ?? 'h2' }}
        class="{{ $titlegroup['tag_style'] ?? 'h2' }} insight ghost delay--2"
    >
        {{ $titlegroup['title'] }}
    </{{ $titlegroup['tag'] ?? 'h2' }} >
@endif
