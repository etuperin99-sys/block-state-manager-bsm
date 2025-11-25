<?php
/**
 * Server-side rendering for BSM Counter block
 *
 * @param array    $attributes Block attributes
 * @param string   $content    Block content
 * @param WP_Block $block      Block instance
 */

defined('ABSPATH') || exit;

$wrapper_attributes = get_block_wrapper_attributes();

// Get initial count from BSM state if set
$initial_count = apply_filters('bsm_get_state', [], 'bsm/counter');
$count = isset($initial_count['bsm/counter']) ? (int) $initial_count['bsm/counter'] : 0;
?>

<div <?php echo $wrapper_attributes; ?> data-bsm-block="counter" data-bsm-key="bsm/counter">
    <div class="bsm-counter">
        <h3 class="bsm-counter__title">BSM Shared Counter</h3>
        <p class="bsm-counter__description">
            This counter shares state with all other counter blocks on the page.
        </p>
        <div class="bsm-counter__display">
            <span class="bsm-counter__value" data-bsm-bind="bsm/counter"><?php echo esc_html($count); ?></span>
        </div>
        <div class="bsm-counter__buttons">
            <button type="button" data-bsm-action="decrement">-</button>
            <button type="button" data-bsm-action="reset">Reset</button>
            <button type="button" class="primary" data-bsm-action="increment">+</button>
        </div>
    </div>
</div>
