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
        'primary_navigation' => __('Primary Navigation', 'sage'),
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
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
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

add_action('after_setup_theme', function () {
    add_image_size('mobile', 768, 0, false);
    add_image_size('tablet', 1500, 0, false);
    add_image_size('desktop', 1920, 0, false);
    add_image_size('bigscreen', 2500, 0, false);
    add_image_size('content-mobile-1024', 1024, 0, false);
    add_image_size('content-desktop-1200', 1200, 0, false);
    add_image_size('content-desktop-2x', 2400, 0, false);
});

add_filter('image_size_names_choose', function ($sizes) {
    return array_merge($sizes, [
        'mobile' => __('Mobile (768px)'),
        'tablet' => __('Tablet (1500px)'),
        'desktop' => __('Desktop (1920px)'),
        'bigscreen' => __('Bigscreen (2500px)'),
        'content-mobile-1024' => __('Content Mobile (1024px)'),
        'content-desktop-1200' => __('Content Desktop (1200px)'),
        'content-desktop-2x' => __('Content Desktop Retina (2400px)'),
    ]);
});

add_filter('sage/blocks/base-buttons/register-data', function ($data) {
    $data['supports']['inserter'] = false;
    return $data;
});

add_filter('sage/blocks/base-title/register-data', function ($data) {
    $data['supports']['inserter'] = false;
    return $data;
});

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
