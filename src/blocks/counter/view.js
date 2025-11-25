/**
 * BSM Counter - Frontend view script
 *
 * Handles interactivity on the frontend using BSM store
 */

import { bsmSelect, bsmDispatch, bsmSubscribe } from '../../store';

document.addEventListener('DOMContentLoaded', () => {
    const counterBlocks = document.querySelectorAll('[data-bsm-block="counter"]');

    if (!counterBlocks.length) return;

    const STATE_KEY = 'bsm/counter';

    // Initialize state if not set
    if (bsmSelect().getState(STATE_KEY) === undefined) {
        bsmDispatch().setState(STATE_KEY, 0);
    }

    // Update all displays when state changes
    const updateDisplays = () => {
        const count = bsmSelect().getState(STATE_KEY) || 0;
        document.querySelectorAll('[data-bsm-bind="bsm/counter"]').forEach((el) => {
            el.textContent = count;
        });
    };

    // Subscribe to state changes
    bsmSubscribe(updateDisplays);

    // Attach event handlers
    counterBlocks.forEach((block) => {
        block.addEventListener('click', (e) => {
            const action = e.target.dataset.bsmAction;
            if (!action) return;

            const currentCount = bsmSelect().getState(STATE_KEY) || 0;

            switch (action) {
                case 'increment':
                    bsmDispatch().setState(STATE_KEY, currentCount + 1);
                    break;
                case 'decrement':
                    bsmDispatch().setState(STATE_KEY, currentCount - 1);
                    break;
                case 'reset':
                    bsmDispatch().setState(STATE_KEY, 0);
                    break;
            }
        });
    });

    // Initial render
    updateDisplays();
});
