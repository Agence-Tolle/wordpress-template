@php
    $logo = get_field('logo_footer', 'options');
    $address = get_field('address', 'options');
    $phone = get_field('phone', 'options');
    $email = get_field('email', 'options');
@endphp

<footer class="bg-black">
    <div class="container">
        <div class="wrap">
            <div class="flex flex-col lg:flex-row items-center lg:items-start justify-center lg:justify-normal gap-10 lg:gap-20 2xl:gap-32 w-full">
                {{-- LOGO --}}
                @if ( !empty($logo) )
                    <a
                        href="<?php echo(function_exists('pll_home_url') ? pll_home_url() : home_url()); ?>"
                        title="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        target="_self"
                        class="logo xl:py-1.5 insight ghost"
                    >
                        @if ( !empty($logo) )
                            <img src="{{ $logo['url'] }}" alt="{{ $logo['alt'] }}" />
                        @endif
                    </a>
                @endif

                {{-- COORDONNÉES --}}
                <div class="flex flex-col gap-6 max-w-60 text-center lg:text-left insight ghost">
                    <p class="text-16 font-bold text-white uppercase">Coordonnées</p>

                    @if ( !empty($address) )
                        <a
                            href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}"
                            title="{{ $address }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-16 text-white hover:text-white/75 duration-300"
                        >
                            {{ $address }}
                        </a>
                    @endif

                    <div class="flex flex-col gap-1">
                        @if ( !empty($phone) )
                            <a
                                href="tel:{{ preg_replace('/\s+/', '', $phone) }}"
                                title="{{ $phone }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-16 text-white hover:text-white/75 duration-300"
                            >
                                {{ $phone }}
                            </a>
                        @endif

                        @if ( !empty($email) )
                            <a
                                href="mailto:{{ $email }}"
                                title="{{ $email }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-16 text-white hover:text-white/75 duration-300"
                            >
                                {{ $email }}
                            </a>
                        @endif
                    </div>
                </div>

                {{-- MENU --}}
                <div class="hidden xl:block insight ghost">
                    @if ( has_nav_menu('footer_navigation') )
                        <nav class="nav-footer" aria-label="{{ wp_get_nav_menu_name('footer_navigation') }}">
                            {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
                        </nav>
                    @endif
                </div>
            </div>
            {{-- LEGAL --}}
            <div class="flex flex-col sm:flex-row justify-center sm:justify-between items-center gap-1.5 py-6 insight ghost">
                <div class="flex flex-col sm:flex-row justify-center sm:justify-start gap-4 sm:gap-12">
                    <a
                        href="<?= get_privacy_policy_url() ?>"
                        target="_self"
                        title="<?= __('Privacy policy','tolle') ?>"
                        class="text-14 text-gray-400 font-medium text-center sm:text-left hover:text-white duration-300"
                    >
                        <?= __('Privacy policy','tolle') ?>
                    </a>
                </div>

                <a
                    href="<?= esc_url('https://www.agencetolle.com/?utm_source=' . rawurlencode(get_bloginfo('name')) . '&utm_medium=web&utm_campaign=client') ?>"
                    target="_blank"
                    title="<?= __('Tollé Web Agency - Application and website development','tolle') ?>"
                    rel="noopener noreferrer"
                    class="group flex items-center duration-300 mt-3 sm:mt-0"
                >

                <span class="text-14 text-gray-400 mr-2 sm:group-hover:opacity-75 sm:opacity-0 duration-500 sm:group-hover:translate-x-0 sm:translate-x-[10%] origin-right">
                    <?= __('Website by','tolle') ?>
                </span>

                @include('svg.tolle')
                </a>
            </div>
        </div>
    </div>
</footer>
