/* eslint-disable no-console */

import axe from 'axe-core';

/**
 * Scan the page for a11y violations
 *
 * @param {(Document | Element | string)} context
 */
export default async function testA11y(context) {
    console.log('Running a11y tests...');
    const options = {
        elementRef: true,
        resultTypes: ['violations'],
    };

    try {
        const results = await axe.run(
            {
                include: [context],
            },
            options
        );

        if (results.violations.length) {
            // Throw an error and return the violations
            throw results.violations;
        }

        console.log('✅ No a11y violations found');
    } catch (error) {
        console.table(error);
    }
}
