/**
 * BSM - Block State Manager
 *
 * Main entry point for the editor
 * Registers the store and exports all public APIs
 */

// Initialize the store
import './store';

// Export hooks
export {
    useBlockState,
    useBlockStates,
    useBlockStateValue,
    useBlockStateDispatch,
} from './hooks';

// Export store utilities
export { STORE_NAME, bsmDispatch, bsmSelect, bsmSubscribe } from './store';

// Log initialization in development
if (process.env.NODE_ENV === 'development') {
    console.log('[BSM] Block State Manager initialized');
}
