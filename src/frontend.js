/**
 * BSM Frontend Store
 *
 * Lightweight store for frontend hydration
 * This runs on the public-facing site to enable reactive blocks
 */

// Simple event-based store for frontend (no wp.data dependency)
class BSMFrontendStore {
    constructor() {
        this.state = {};
        this.listeners = new Set();

        // Initialize with server-provided state
        if (typeof window !== 'undefined' && window.bsmInitialState) {
            this.state = { ...window.bsmInitialState };
        }
    }

    getState(key) {
        return this.state[key];
    }

    getAllState() {
        return { ...this.state };
    }

    setState(key, value) {
        this.state[key] = value;
        this.notify();
    }

    setMultiple(payload) {
        this.state = { ...this.state, ...payload };
        this.notify();
    }

    deleteKey(key) {
        delete this.state[key];
        this.notify();
    }

    subscribe(callback) {
        this.listeners.add(callback);
        return () => this.listeners.delete(callback);
    }

    notify() {
        this.listeners.forEach((callback) => callback(this.state));
    }
}

// Create global store instance
const store = new BSMFrontendStore();

// Expose globally for blocks
window.bsmStore = store;

// Export functions matching the editor API
export const bsmSelect = () => ({
    getState: (key) => store.getState(key),
    getAllState: () => store.getAllState(),
});

export const bsmDispatch = () => ({
    setState: (key, value) => store.setState(key, value),
    setMultiple: (payload) => store.setMultiple(payload),
    deleteKey: (key) => store.deleteKey(key),
});

export const bsmSubscribe = (callback) => store.subscribe(callback);

// Log initialization
console.log('[BSM] Frontend store initialized');
