# Block State Manager (BSM)

[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**React-style global state management for Gutenberg blocks**

BSM brings modern state management to WordPress Gutenberg, enabling blocks to share and synchronize state without REST API calls or complex event systems.

---

## Table of Contents

- [The Problem](#the-problem)
- [The Solution](#the-solution)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [API Reference](#api-reference)
- [Examples](#examples)
- [Demo Blocks](#demo-blocks)
- [Documentation Site](#documentation-site)
- [Contributing](#contributing)
- [License](#license)

---

## The Problem

Gutenberg blocks are isolated by design. They can't easily communicate with each other:

| Challenge | Traditional Solution | Issues |
|-----------|---------------------|--------|
| Cross-block data sharing | REST API calls | Slow, server load |
| Real-time sync | Custom events | Complex, error-prone |
| Shared state | Prop drilling | Only works in hierarchy |
| Reactive updates | Manual subscriptions | Boilerplate code |

## The Solution

BSM provides a **global state store** that any block can read from and write to:

```jsx
// Block A sets state
const [count, setCount] = useBlockState('app/counter', 0);
setCount(5);

// Block B (anywhere on the page) sees the same state immediately
const [count] = useBlockState('app/counter', 0);
// count === 5
```

**Key benefits:**
- One shared state for all blocks
- Instant synchronization (no API calls)
- Familiar React hooks API
- Works in editor and frontend
- Built on WordPress Data API

---

## Installation

### From GitHub

```bash
cd wp-content/plugins
git clone https://github.com/etuperin99-sys-sys/block-state-manager-bsm.git block-state-manager
cd block-state-manager
npm install
npm run build
```

### Manual Installation

1. Download the latest release
2. Upload to `wp-content/plugins/block-state-manager`
3. Activate in WordPress admin

---

## Quick Start

### 1. Import the Hook

```jsx
import { useBlockState } from 'bsm-store';
```

### 2. Use in Your Block

```jsx
const Edit = () => {
    const [count, setCount] = useBlockState('myapp/counter', 0);

    return (
        <div>
            <p>Count: {count}</p>
            <button onClick={() => setCount(c => c + 1)}>+</button>
        </div>
    );
};
```

### 3. Add Dependency

In your block's `block.json`:

```json
{
    "editorScript": "file:./index.js",
    "editorDependencies": ["bsm-store"]
}
```

Or in PHP:

```php
wp_register_script(
    'my-block',
    plugin_dir_url(__FILE__) . 'build/index.js',
    ['bsm-store', 'wp-blocks', 'wp-element'],
    '1.0.0'
);
```

---

## API Reference

### `useBlockState(key, initialValue)`

Primary hook for reading and writing state.

```jsx
const [value, setValue, helpers] = useBlockState('namespace/key', defaultValue);

// Read
console.log(value);

// Write
setValue(newValue);

// Functional update
setValue(prev => ({ ...prev, updated: true }));

// Helpers
helpers.reset();         // Reset to initial value
helpers.remove();        // Delete from state
helpers.update({...});   // Partial object merge
helpers.isInitial;       // Boolean
```

### `useBlockStateValue(key, defaultValue)`

Read-only hook for observing state.

```jsx
const count = useBlockStateValue('app/counter', 0);
```

### `useBlockStates(keysWithDefaults)`

Read multiple state values.

```jsx
const state = useBlockStates({
    'form/name': '',
    'form/email': '',
    'form/step': 0
});
```

### `useBlockStateDispatch()`

Direct access to dispatch functions.

```jsx
const { setState, setMultiple, deleteKey, resetState } = useBlockStateDispatch();
```

### Direct Store Access

```jsx
import { bsmSelect, bsmDispatch, bsmSubscribe, STORE_NAME } from 'bsm-store';

bsmSelect().getState('key');
bsmDispatch().setState('key', value);
bsmSubscribe(callback);
```

### PHP Functions

```php
// Set initial state for frontend hydration
bsm_set_initial_state('shop/cart', $cart_data);

// Get state (requires state sync)
$value = bsm_get_state('key', $default);
```

---

## Examples

### Shopping Cart

```jsx
// Product Block
const ProductBlock = ({ product }) => {
    const [cart, setCart] = useBlockState('shop/cart', []);

    return (
        <button onClick={() => setCart(c => [...c, product])}>
            Add to Cart
        </button>
    );
};

// Cart Block (reads same state)
const CartBlock = () => {
    const [cart] = useBlockState('shop/cart', []);
    return <div>Items: {cart.length}</div>;
};
```

### Search + Results

```jsx
// Search Block
const SearchBlock = () => {
    const [query, setQuery] = useBlockState('search/query', '');
    return <input value={query} onChange={e => setQuery(e.target.value)} />;
};

// Results Block
const ResultsBlock = () => {
    const query = useBlockStateValue('search/query', '');
    // Filter/display results based on query
};
```

### Multi-Step Form

```jsx
const [form, setForm] = useBlockState('form/data', {
    step: 1,
    name: '',
    email: ''
});

// Step 1, Step 2, Summary blocks all use same state
```

### Gallery + Lightbox

```jsx
// Gallery
const [, setLightbox] = useBlockState('gallery/lightbox', { open: false, index: 0 });
<img onClick={() => setLightbox({ open: true, index: i })} />

// Lightbox
const [lightbox, setLightbox] = useBlockState('gallery/lightbox', { open: false, index: 0 });
if (lightbox.open) return <Lightbox index={lightbox.index} />;
```

---

## Demo Blocks

BSM includes three demo blocks:

| Block | Description |
|-------|-------------|
| **BSM Counter** | Multiple counters sharing same count |
| **BSM Shared Input** | Input that broadcasts to Display blocks |
| **BSM Display** | Shows text from Shared Input blocks |

Find them in the Gutenberg block inserter by searching "BSM".

---

## Documentation Site

Full documentation available at:

**[https://etuperin99-sys.github.io/block-state-manager-bsm](https://etuperin99-sys.github.io/block-state-manager-bsm)**

Or access the built-in documentation in WordPress Admin → BSM.

---

## State Key Convention

Use namespaced keys to avoid conflicts:

```jsx
// Good
useBlockState('shop/cart', [])
useBlockState('myapp/settings', {})

// Avoid
useBlockState('data', {})
useBlockState('value', '')
```

---

## Development

```bash
# Install dependencies
npm install

# Development build with watch
npm start

# Production build
npm run build

# Lint
npm run lint:js
npm run lint:css
```

---

## Requirements

- WordPress 6.0+
- PHP 7.4+
- Node.js 16+ (for development)

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

## License

MIT License - see [LICENSE](LICENSE) file.

---

## Credits

Created by [etuperin99](https://github.com/etuperin99-sys)

Built with:
- WordPress Data API (`wp.data`)
- React Hooks
- @wordpress/scripts
