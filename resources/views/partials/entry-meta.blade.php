<time class="dt-published" datetime="{{ get_post_time('c', true) }}">
  {{ get_the_date() }}
</time>

<p>
  <span>{{ __('By', 'sage') }}</span>
  <a
    href="{{ get_author_posts_url(get_the_author_meta('ID')) }}"
    title="{{ get_the_author() }}"
    target="_self"
    class="p-author h-card"
  >
    {{ get_the_author() }}
  </a>
</p>
