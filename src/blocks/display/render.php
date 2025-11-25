<?php
/**
 * Server-side rendering for BSM Display block
 */

defined('ABSPATH') || exit;

$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?> data-bsm-block="display">
    <div class="bsm-display">
        <h3 class="bsm-display__title">BSM Display</h3>
        <p class="bsm-display__description">
            This block displays text from BSM Shared Input blocks.
        </p>
        <div class="bsm-display__output" data-bsm-bind="bsm/shared-text">
            <span class="bsm-display__placeholder">Waiting for input...</span>
        </div>
    </div>
</div>
