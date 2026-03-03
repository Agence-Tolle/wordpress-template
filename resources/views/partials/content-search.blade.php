<article @php(post_class())>
  <header>
    <h2 class="entry-title">
      <a
        href="{{ get_permalink() }}"
        title="{{ the_title_attribute(['echo' => false]) }}"
        target="_self"
      >
        {!! $title !!}
      </a>
    </h2>

    @includeWhen(get_post_type() === 'post', 'partials.entry-meta')
  </header>

  <div class="entry-summary">
    @php(the_excerpt())
  </div>
</article>
