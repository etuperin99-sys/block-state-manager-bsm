/**
 * useBlockState Hook
 *
 * React-style hook for accessing and updating BSM global state.
 * Works similar to useState but with global scope across all blocks.
 *
 * @example
 * const [count, setCount] = useBlockState('myblock/counter', 0);
 * const [user, setUser] = useBlockState('form/user', { name: '', email: '' });
 */

import { useSelect, useDispatch } from '@wordpress/data';
import { useCallback, useMemo } from '@wordpress/element';
import { STORE_NAME } from '../store';

/**
 * Hook for managing global block state
 *
 * @param {string} key - Unique key for the state (recommend namespace/name format)
 * @param {*} initialValue - Initial value if state doesn't exist
 * @returns {[*, Function, Object]} - [value, setValue, helpers]
 */
export function useBlockState(key, initialValue = undefined) {
    // Select state value
    const value = useSelect(
        (select) => {
            const state = select(STORE_NAME).getState(key);
            return state !== undefined ? state : initialValue;
        },
        [key, initialValue]
    );

    // Get dispatch
    const { setState, deleteKey } = useDispatch(STORE_NAME);

    // Memoized setValue function
    const setValue = useCallback(
        (newValue) => {
            // Support functional updates like useState
            if (typeof newValue === 'function') {
                const currentValue = value;
                setState(key, newValue(currentValue));
            } else {
                setState(key, newValue);
            }
        },
        [key, value, setState]
    );

    // Helper functions
    const helpers = useMemo(
        () => ({
            /**
             * Reset to initial value
             */
            reset: () => setState(key, initialValue),

            /**
             * Delete this key from state
             */
            remove: () => deleteKey(key),

            /**
             * Update object state partially (merge)
             */
            update: (partial) => {
                if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
                    setState(key, { ...value, ...partial });
                } else {
                    console.warn('useBlockState.update() only works with object values');
                }
            },

            /**
             * Check if value is the initial value
             */
            isInitial: value === initialValue ||
                JSON.stringify(value) === JSON.stringify(initialValue),
        }),
        [key, value, initialValue, setState, deleteKey]
    );

    return [value, setValue, helpers];
}

/**
 * Hook for reading multiple state values at once
 *
 * @param {Object} keysWithDefaults - Object with keys and their default values
 * @returns {Object} - Object with current values
 *
 * @example
 * const state = useBlockStates({
 *   'form/name': '',
 *   'form/email': '',
 *   'form/step': 0
 * });
 */
export function useBlockStates(keysWithDefaults) {
    const keys = Object.keys(keysWithDefaults);

    return useSelect(
        (select) => {
            const { getState } = select(STORE_NAME);
            return keys.reduce((acc, key) => {
                const value = getState(key);
                acc[key] = value !== undefined ? value : keysWithDefaults[key];
                return acc;
            }, {});
        },
        [keys.join(',')]
    );
}

/**
 * Hook for subscribing to state changes (read-only)
 *
 * @param {string} key - State key to watch
 * @param {*} defaultValue - Default value if not set
 * @returns {*} - Current value
 */
export function useBlockStateValue(key, defaultValue = undefined) {
    return useSelect(
        (select) => {
            const value = select(STORE_NAME).getState(key);
            return value !== undefined ? value : defaultValue;
        },
        [key, defaultValue]
    );
}

/**
 * Hook for getting the dispatch function only
 *
 * @returns {Object} - Dispatch functions
 */
export function useBlockStateDispatch() {
    return useDispatch(STORE_NAME);
}

export default useBlockState;
