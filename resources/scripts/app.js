import Alpine from 'alpinejs';
import testA11y from './utils/test-a11y.js';

// eslint-disable-next-line no-undef
window.Alpine = Alpine;

Alpine.start();

// If we have the test_a11y query param, run the a11y tests
if (window.location.search.includes('test_a11y')) {
    testA11y(document);
}

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error); // eslint-disable-line no-console
