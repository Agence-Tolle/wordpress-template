<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => Vite::isRunningHot()
            ? "@import url('{$style}')"
            : Vite::content('resources/css/editor.css'),
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

function vite_login_asset($entry) {
    $manifest_path = get_theme_file_path('public/build/manifest.json');

    if (!file_exists($manifest_path)) {
        return null;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (!isset($manifest[$entry]) || !isset($manifest[$entry]['file'])) {
        return null;
    }

    return get_theme_file_uri('public/build/' . $manifest[$entry]['file']);
}

add_action('login_enqueue_scripts', function () {
    $login_css = vite_login_asset('resources/css/login.css');

    if ($login_css) {
        wp_enqueue_style('sage/login.css', $login_css, [], null);
    }
});

/**
 * Add Vite's HMR client to the block editor.
 *
 * @return void
 */
add_action('enqueue_block_assets', function () {
    if (! is_admin() || ! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        return;
    }

    $script = sprintf(
        <<<'JS'
        window.__vite_client_url = '%s';

        window.self !== window.top && document.head.appendChild(
            Object.assign(document.createElement('script'), { type: 'module', src: '%s' })
        );
        JS,
        untrailingslashit(Vite::asset('')),
        Vite::asset('@vite/client')
    );

    wp_add_inline_script('wp-blocks', $script);
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'tolle'),
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'tolle'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'tolle'),
        'id' => 'sidebar-footer',
    ] + $config);
});

/**
 * Disable the comments section.
 *
 * @return void
 */
add_action('init', function () {
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }

    // Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Remove comments page in menu
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });

    // Redirect any user trying to access comments page
    add_action('admin_init', function () {
        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    });

    // Remove comments metabox from dashboard
    add_action('admin_init', function () {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    });

    // Remove comments links from admin bar
    add_action('wp_before_admin_bar_render', function () {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
});

function retina_image_registry(): array
{
    return [
        'mobile' => ['width' => 768, 'label' => __('Mobile (768px)', 'tolle')],
        'mobile-2x' => ['width' => 1536, 'label' => __('Mobile Retina (1536px)', 'tolle')],
        'content' => ['width' => 768, 'label' => __('Content (768px)', 'tolle')],
        'content-2x' => ['width' => 1536, 'label' => __('Content Retina (1536px)', 'tolle')],
        'content-mobile-1024' => ['width' => 1024, 'label' => __('Content Mobile (1024px)', 'tolle')],
        'content-wide' => ['width' => 1200, 'label' => __('Content Wide (1200px)', 'tolle')],
        'content-wide-2x' => ['width' => 2400, 'label' => __('Content Wide Retina (2400px)', 'tolle')],
        'content-desktop-1200' => ['width' => 1200, 'label' => __('Content Desktop (1200px)', 'tolle')],
        'content-desktop-2x' => ['width' => 2400, 'label' => __('Content Desktop Retina (2400px)', 'tolle')],
        'layout-half' => ['width' => 780, 'label' => __('Layout Half (780px)', 'tolle')],
        'layout-half-2x' => ['width' => 1560, 'label' => __('Layout Half Retina (1560px)', 'tolle')],
        'layout-full' => ['width' => 1560, 'label' => __('Layout Full (1560px)', 'tolle')],
        'layout-full-2x' => ['width' => 3120, 'label' => __('Layout Full Retina (3120px)', 'tolle')],
        'tablet' => ['width' => 1500, 'label' => __('Tablet (1500px)', 'tolle')],
        'desktop' => ['width' => 1920, 'label' => __('Desktop (1920px)', 'tolle')],
        'bigscreen' => ['width' => 2500, 'label' => __('Bigscreen (2500px)', 'tolle')],
    ];
}

function retina_image_sizes(string $context = 'content'): string
{
    return match ($context) {
        'layout-full' => '100vw',
        'layout-half' => '(max-width: 767px) 100vw, (max-width: 1599px) calc((100vw - 5.5rem) / 2), 780px',
        'content-wide' => '(max-width: 767px) 100vw, (max-width: 1279px) calc(100vw - 2rem), 1200px',
        'icon' => '15px',
        default => '(max-width: 767px) 100vw, 768px',
    };
}

function retina_image_size(string $context = 'content', bool $retina = true): string
{
    return match ($context) {
        'layout-full' => $retina ? 'layout-full-2x' : 'layout-full',
        'layout-half' => $retina ? 'layout-half-2x' : 'layout-half',
        'content-wide' => $retina ? 'content-wide-2x' : 'content-wide',
        'icon' => 'thumbnail',
        default => $retina ? 'content-2x' : 'content',
    };
}

function retina_image_context_from_size($size): string
{
    if (is_array($size)) {
        $width = (int) ($size[0] ?? 0);

        if ($width > 1200) {
            return 'layout-full';
        }

        if ($width > 900) {
            return 'content-wide';
        }

        if ($width > 100) {
            return 'content';
        }

        return 'icon';
    }

    return match ($size) {
        'layout-full',
        'layout-full-2x',
        'desktop',
        'bigscreen' => 'layout-full',
        'layout-half',
        'layout-half-2x' => 'layout-half',
        'content-wide',
        'content-wide-2x',
        'content-desktop-1200',
        'content-desktop-2x',
        'content-mobile-1024',
        'tablet' => 'content-wide',
        'thumbnail',
        'icon' => 'icon',
        default => 'content',
    };
}

add_action('after_setup_theme', function () {
    foreach (retina_image_registry() as $name => $config) {
        add_image_size($name, $config['width'], 0, false);
    }
});

add_filter('image_size_names_choose', function ($sizes) {
    $customSizes = [];

    foreach (retina_image_registry() as $name => $config) {
        $customSizes[$name] = $config['label'];
    }

    return array_merge($sizes, $customSizes);
});

add_filter('big_image_size_threshold', function () {
    return 3840;
});

add_filter('max_srcset_image_width', function ($maxWidth) {
    return max((int) $maxWidth, 3840);
});

add_filter('wp_calculate_image_sizes', function ($sizes, $size, $imageSrc, $imageMeta, $attachmentId) {
    if (is_admin()) {
        return $sizes;
    }

    return retina_image_sizes('content');
}, 10, 5);

add_filter('wp_get_attachment_image_attributes', function ($attributes, $attachment, $size) {
    if (is_admin() || ! empty($attributes['sizes'])) {
        return $attributes;
    }

    if (get_post_mime_type($attachment) === 'image/svg+xml') {
        return $attributes;
    }

    $attributes['sizes'] = retina_image_sizes(retina_image_context_from_size($size));

    return $attributes;
}, 10, 3);

/**
 * Restreint l'inserteur de blocs aux seuls blocs ACF (Blocs Tollé)
 * lors de l'édition d'un article ou d'une page.
 */
add_filter('allowed_block_types_all', function ($allowed_blocks, $context) {
    if (empty($context->post)) {
        return $allowed_blocks;
    }

    $registered = array_keys(\WP_Block_Type_Registry::get_instance()->get_all_registered());

    return array_values(array_filter($registered, function ($name) {
        return str_starts_with($name, 'acf/');
    }));
}, 10, 2);

add_filter('sage/blocks/base-buttons/register-data', function ($data) {
    $data['supports']['inserter'] = false;
    return $data;
});

add_filter('sage/blocks/base-title/register-data', function ($data) {
    $data['supports']['inserter'] = false;
    return $data;
});

/**
 * Affiche une capture d'écran comme aperçu d'un bloc ACF dans l'inserteur.
 * Il suffit de déposer une image "{slug}.png" (ou .jpg) dans
 * resources/images/block-previews/ — le rendu du bloc est alors remplacé
 * par cette image dans l'aperçu de l'inserteur, sans toucher au template.
 */
function block_preview_image($slug)
{
    foreach (['png', 'jpg'] as $extension) {
        $file = "resources/images/block-previews/{$slug}.{$extension}";

        if (file_exists(get_theme_file_path($file))) {
            return $file;
        }
    }

    return null;
}

foreach (glob(get_theme_file_path('resources/views/blocks/*.blade.php')) as $block_template) {
    $block_slug = basename($block_template, '.blade.php');

    add_filter("sage/blocks/{$block_slug}/register-data", function ($data) use ($block_slug) {
        $preview = block_preview_image($block_slug);

        if (! $preview) {
            return $data;
        }

        $data['example'] = [
            'attributes' => [
                'mode' => 'preview',
                'data' => [
                    '_inserter_preview' => true,
                ],
            ],
        ];

        $render_callback = $data['render_callback'];

        // Court-circuite le rendu du bloc lors de l'aperçu dans l'inserteur
        // pour afficher la capture d'écran à la place.
        $data['render_callback'] = function ($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = null, $context = false) use ($render_callback, $preview) {
            if (! empty($block['data']['_inserter_preview'])) {
                printf(
                    '<img src="%s" style="display: block; width: 100%%; height: auto;" alt="">',
                    esc_url(get_theme_file_uri($preview))
                );

                return;
            }

            call_user_func($render_callback, $block, $content, $is_preview, $post_id, $wp_block, $context);
        };

        return $data;
    });
}

function acf_populate_gf_forms_ids($field)
{
    if (class_exists('GFFormsModel')) {
        $choices = [];

        foreach (\GFFormsModel::get_forms() as $form) {
            $choices[$form->id] = $form->title;
        }

        $field['choices'] = $choices;
    }

    return $field;
}

add_filter('acf/load_field/name=gravity_form_id', 'acf_populate_gf_forms_ids');
