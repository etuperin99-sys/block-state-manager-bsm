<?php
/**
 * Plugin Name: Block State Manager (BSM)
 * Plugin URI: https://gitlab.com/etuperin99/block-state-manager-bsm
 * Description: React-style global state management for Gutenberg editor and frontend
 * Version: 0.1.0
 * Author: etuperin99
 * License: MIT
 * Text Domain: block-state-manager
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

define('BSM_VERSION', '0.1.0');
define('BSM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BSM_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Initialize the BSM plugin
 */
class BlockStateManager {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'register_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
    }

    /**
     * Register the BSM store and core scripts for the editor
     */
    public function enqueue_editor_assets() {
        $asset_file = BSM_PLUGIN_DIR . 'build/bsm-store.asset.php';
        $asset = file_exists($asset_file)
            ? require $asset_file
            : ['dependencies' => ['wp-data', 'wp-element', 'wp-components', 'wp-block-editor'], 'version' => BSM_VERSION];

        wp_enqueue_script(
            'bsm-store',
            BSM_PLUGIN_URL . 'build/bsm-store.js',
            $asset['dependencies'],
            $asset['version'],
            true
        );

        wp_enqueue_style(
            'bsm-editor-styles',
            BSM_PLUGIN_URL . 'build/bsm-editor.css',
            [],
            BSM_VERSION
        );
    }

    /**
     * Enqueue frontend assets for hydration
     */
    public function enqueue_frontend_assets() {
        if (!$this->should_load_frontend()) {
            return;
        }

        $asset_file = BSM_PLUGIN_DIR . 'build/bsm-frontend.asset.php';
        $asset = file_exists($asset_file)
            ? require $asset_file
            : ['dependencies' => [], 'version' => BSM_VERSION];

        wp_enqueue_script(
            'bsm-frontend',
            BSM_PLUGIN_URL . 'build/bsm-frontend.js',
            $asset['dependencies'],
            $asset['version'],
            true
        );

        // Pass initial state to frontend
        $initial_state = apply_filters('bsm_initial_frontend_state', []);
        wp_localize_script('bsm-frontend', 'bsmInitialState', $initial_state);
    }

    /**
     * Check if we should load frontend scripts
     */
    private function should_load_frontend() {
        // Load if post contains BSM blocks
        global $post;
        if (!$post) {
            return false;
        }

        return has_block('bsm/', $post) || apply_filters('bsm_force_frontend_load', false);
    }

    /**
     * Register example blocks
     */
    public function register_blocks() {
        // Register Counter block
        if (file_exists(BSM_PLUGIN_DIR . 'build/blocks/counter/block.json')) {
            register_block_type(BSM_PLUGIN_DIR . 'build/blocks/counter');
        }

        // Register Shared Input block
        if (file_exists(BSM_PLUGIN_DIR . 'build/blocks/shared-input/block.json')) {
            register_block_type(BSM_PLUGIN_DIR . 'build/blocks/shared-input');
        }

        // Register Display block
        if (file_exists(BSM_PLUGIN_DIR . 'build/blocks/display/block.json')) {
            register_block_type(BSM_PLUGIN_DIR . 'build/blocks/display');
        }
    }
}

// Initialize
BlockStateManager::get_instance();

/**
 * Helper function to get BSM state from PHP (for SSR)
 */
function bsm_get_state($key, $default = null) {
    $state = apply_filters('bsm_get_state', [], $key);
    return $state[$key] ?? $default;
}

/**
 * Helper function to set initial state from PHP
 */
function bsm_set_initial_state($key, $value) {
    add_filter('bsm_initial_frontend_state', function($state) use ($key, $value) {
        $state[$key] = $value;
        return $state;
    });
}
