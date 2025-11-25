/**
 * BSM Display - Frontend view script
 */

import { bsmSelect, bsmSubscribe } from '../../store';

document.addEventListener('DOMContentLoaded', () => {
    const displays = document.querySelectorAll('[data-bsm-bind="bsm/shared-text"]');

    if (!displays.length) return;

    const STATE_KEY = 'bsm/shared-text';

    // Update displays when state changes
    const updateDisplays = () => {
        const text = bsmSelect().getState(STATE_KEY) || '';
        displays.forEach((el) => {
            if (text) {
                el.innerHTML = text;
            } else {
                el.innerHTML = '<span class="bsm-display__placeholder">Waiting for input...</span>';
            }
        });
    };

    // Subscribe to state changes
    bsmSubscribe(updateDisplays);

    // Initial render
    updateDisplays();
});
