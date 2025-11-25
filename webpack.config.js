/**
 * WordPress Scripts Webpack Configuration
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        // Main BSM store (for editor)
        'bsm-store': path.resolve(__dirname, 'src/index.js'),

        // Frontend store
        'bsm-frontend': path.resolve(__dirname, 'src/frontend.js'),

        // Blocks
        'blocks/counter/index': path.resolve(__dirname, 'src/blocks/counter/index.js'),
        'blocks/counter/view': path.resolve(__dirname, 'src/blocks/counter/view.js'),
        'blocks/shared-input/index': path.resolve(__dirname, 'src/blocks/shared-input/index.js'),
        'blocks/shared-input/view': path.resolve(__dirname, 'src/blocks/shared-input/view.js'),
        'blocks/display/index': path.resolve(__dirname, 'src/blocks/display/index.js'),
        'blocks/display/view': path.resolve(__dirname, 'src/blocks/display/view.js'),
    },
    output: {
        ...defaultConfig.output,
        path: path.resolve(__dirname, 'build'),
    },
};
