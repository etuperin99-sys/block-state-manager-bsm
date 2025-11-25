/**
 * BSM Shared Input - Frontend view script
 */

import { bsmSelect, bsmDispatch, bsmSubscribe } from '../../store';

document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('[data-bsm-input="bsm/shared-text"]');

    if (!inputs.length) return;

    const STATE_KEY = 'bsm/shared-text';

    // Initialize state
    if (bsmSelect().getState(STATE_KEY) === undefined) {
        bsmDispatch().setState(STATE_KEY, '');
    }

    // Sync all inputs with state
    const syncInputs = () => {
        const text = bsmSelect().getState(STATE_KEY) || '';
        inputs.forEach((input) => {
            if (document.activeElement !== input) {
                input.value = text;
            }
        });
    };

    // Subscribe to changes
    bsmSubscribe(syncInputs);

    // Attach input handlers
    inputs.forEach((input) => {
        input.addEventListener('input', (e) => {
            bsmDispatch().setState(STATE_KEY, e.target.value);
        });
    });

    // Initial sync
    syncInputs();
});
