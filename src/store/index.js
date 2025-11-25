/**
 * BSM Store - Global state management for Gutenberg blocks
 *
 * This is the core store that enables cross-block communication
 * using WordPress Data API (wp.data)
 */

import { createReduxStore, register, dispatch, select, subscribe } from '@wordpress/data';

const STORE_NAME = 'bsm/store';

/**
 * Default state
 */
const DEFAULT_STATE = {};

/**
 * Action types
 */
const SET_STATE = 'SET_STATE';
const SET_MULTIPLE = 'SET_MULTIPLE';
const RESET_STATE = 'RESET_STATE';
const DELETE_KEY = 'DELETE_KEY';

/**
 * Reducer
 */
const reducer = (state = DEFAULT_STATE, action) => {
    switch (action.type) {
        case SET_STATE:
            return {
                ...state,
                [action.key]: action.value,
            };

        case SET_MULTIPLE:
            return {
                ...state,
                ...action.payload,
            };

        case DELETE_KEY:
            const { [action.key]: deleted, ...rest } = state;
            return rest;

        case RESET_STATE:
            return action.initialState || DEFAULT_STATE;

        default:
            return state;
    }
};

/**
 * Actions
 */
const actions = {
    /**
     * Set a single state value
     * @param {string} key - State key (namespace/name format recommended)
     * @param {*} value - The value to set
     */
    setState(key, value) {
        return {
            type: SET_STATE,
            key,
            value,
        };
    },

    /**
     * Set multiple state values at once
     * @param {Object} payload - Object with key-value pairs
     */
    setMultiple(payload) {
        return {
            type: SET_MULTIPLE,
            payload,
        };
    },

    /**
     * Delete a state key
     * @param {string} key - State key to delete
     */
    deleteKey(key) {
        return {
            type: DELETE_KEY,
            key,
        };
    },

    /**
     * Reset entire state
     * @param {Object} initialState - Optional initial state
     */
    resetState(initialState = DEFAULT_STATE) {
        return {
            type: RESET_STATE,
            initialState,
        };
    },
};

/**
 * Selectors
 */
const selectors = {
    /**
     * Get a state value
     * @param {Object} state - The store state
     * @param {string} key - State key
     * @returns {*} The state value
     */
    getState(state, key) {
        return state[key];
    },

    /**
     * Get entire state object
     * @param {Object} state - The store state
     * @returns {Object} Complete state
     */
    getAllState(state) {
        return state;
    },

    /**
     * Check if a key exists
     * @param {Object} state - The store state
     * @param {string} key - State key
     * @returns {boolean}
     */
    hasKey(state, key) {
        return key in state;
    },

    /**
     * Get multiple keys at once
     * @param {Object} state - The store state
     * @param {string[]} keys - Array of state keys
     * @returns {Object} Object with requested key-value pairs
     */
    getMultiple(state, keys) {
        return keys.reduce((acc, key) => {
            if (key in state) {
                acc[key] = state[key];
            }
            return acc;
        }, {});
    },
};

/**
 * Create and register the store
 */
const store = createReduxStore(STORE_NAME, {
    reducer,
    actions,
    selectors,
});

register(store);

/**
 * Export store name for external usage
 */
export { STORE_NAME };

/**
 * Export convenience functions
 */
export const bsmDispatch = () => dispatch(STORE_NAME);
export const bsmSelect = () => select(STORE_NAME);
export const bsmSubscribe = (callback) => subscribe(callback);

/**
 * Initialize with server-side state if available
 */
if (typeof window !== 'undefined' && window.bsmInitialState) {
    dispatch(STORE_NAME).setMultiple(window.bsmInitialState);
}

export default store;
