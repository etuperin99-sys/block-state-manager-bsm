<?php
/**
 * Server-side rendering for BSM Shared Input block
 */

defined('ABSPATH') || exit;

$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?> data-bsm-block="shared-input">
    <div class="bsm-shared-input">
        <h3 class="bsm-shared-input__title">BSM Shared Input</h3>
        <p class="bsm-shared-input__description">
            Type here and see the text appear in all BSM Display blocks!
        </p>
        <input
            type="text"
            class="bsm-shared-input__field"
            data-bsm-input="bsm/shared-text"
            placeholder="Type something..."
        />
    </div>
</div>
