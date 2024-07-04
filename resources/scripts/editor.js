import domReady from '@roots/sage/client/dom-ready'; // eslint-disable-line
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks'; // eslint-disable-line

/**
 * Editor entrypoint
 */
domReady(() => {
    unregisterBlockStyle('core/button', 'outline');

    registerBlockStyle('core/button', {
        name: 'outline',
        label: 'Outline',
    });
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
// eslint-disable-next-line
import.meta.webpackHot?.accept(console.error);
