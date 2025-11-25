# Block State Manager (BSM)

## React-style Global State for Gutenberg Editor and Frontend

**Version:** 0.1-RFC
**Purpose:** Bring a modern, centralized state (store) to the Gutenberg ecosystem, enabling blocks to communicate with each other without manual events, prop-drilling, or REST/Custom API updates.

---

## 1. Background and Problem

Gutenberg blocks were not originally designed to share state with each other. Because of this:

* Each block primarily manages its own state
* Synchronization between multiple blocks is complex
* E.g., gallery → lightbox → fullscreen → thumbnails never natively know about each other

### Practical Bottlenecks

| Problem | Explanation |
|---------|-------------|
| No global state architecture | Blocks are isolated |
| Prop-drilling | Data flows only up-down within own block hierarchy |
| REST endpoint required | All cross-block logic moves to PHP → REST → JS |
| No reactivity | Other blocks don't know when something changes |
| Inexperienced devs can't build complex UI | Gutenberg doesn't offer React-context / global store pattern |

BSM solves all of this.

---

## 2. Solution: Block State Manager (BSM)

BSM is:

* Gutenberg's global state engine
* React Context + Redux-inspired architecture
* Works in both editor and frontend
* Synchronizes state in real-time between all blocks
* Enables cross-block communication without API calls

### BSM Core Concept

```js
const [state, setState] = useBlockState('namespace/key', {
    initialValue: { open: false, index: 0 }
});
```

A block can:

* Listen to global state
* Update it
* React to changes like a React hook

---

## 3. Architecture

### 3.1. Store + Actions (Redux-lite)

#### Store

* One shared "object tree"
* Shared between all blocks and all instances
* Stored within Gutenberg's Data API (wp.data)

#### Actions

* Every state update goes through a single, immutable update
* Guarantees predictability

#### Selectors

* Blocks can easily read state

#### Subscribers

* React to changes without prop-drilling

### 3.2. React Hook API

```js
const [cart, setCart] = useBlockState('shop/cart', { items: [] });
```

---

## 4. Use Cases

### 4.1. Map + Search Field + Listing Block

**Without BSM:** All communication via REST API or custom events.
**With BSM:** State is automatically shared between blocks.

* Search field updates state: `searchTerm`
* Map reads `searchTerm` and fetches pins
* Listing block renders items automatically

### 4.2. Gallery + Lightbox + Slider

* Lightbox knows which image is selected
* Gallery updates `activeIndex`
* Slider reacts to the same index
* Everything stays in sync

### 4.3. Multi-step Forms

* Step 1, Step 2, and summary block
* All share the same state

```js
useBlockState('form/user', { name: '', email: '' });
```

---

## 5. Technical Benefits

### 5.1. No REST Endpoints for Every Small Change

State flows directly between blocks → backend load decreases significantly.

### 5.2. Gutenberg Becomes React

BSM brings:

* Context-style architecture
* Redux/Signals-level global state management
* Modern JS development pattern to WordPress

### 5.3. Better Performance

* No unnecessary renders
* No unnecessary AJAX / REST calls
* Reactive data directly in memory

---

## 6. Editor + Frontend Consistency

BSM works in:

1. Gutenberg editor
2. Frontend (as hydratable)

State can be:

* Serialized via `post_meta` or `block attributes`
* Init-loaded to frontend
* Combined into one unified state

---

## 7. Code Example: Simple Store

### 1. Register Store

```js
wp.data.registerStore('bsm/store', {
    reducer(state = {}, action) {
        switch (action.type) {
            case 'SET_STATE':
                return { ...state, [action.key]: action.value };
        }
        return state;
    },
    actions: {
        setState(key, value) {
            return { type: 'SET_STATE', key, value };
        }
    },
    selectors: {
        getState(state, key) {
            return state[key];
        },
    }
});
```

### 2. Hook

```js
export function useBlockState(key, initialValue) {
    const value = useSelect(select =>
        select('bsm/store').getState(key) ?? initialValue,
        [key]
    );

    const setValue = (newValue) => {
        dispatch('bsm/store').setState(key, newValue);
    };

    return [value, setValue];
}
```

---

## 8. Why This Would Be a Historic Update for Gutenberg

### 8.1. WordPress UI Development Moves to Modern Era

Gutenberg doesn't have a modern state management layer.

### 8.2. Complex Applications Finally Within Blocks

* Maps
* Marketplaces
* Dashboards
* Timelines
* Live visualizations

### 8.3. WordPress Becomes Competitive

Wix | Webflow | Builder.io → all have global reactive state.
WordPress doesn't. BSM fixes this.

---

## 9. Next Steps

1. Write RFC to GitHub
2. Implement prototype plugin
3. Build 2–3 block examples
4. Pitch to:
   * Gutenberg Core team
   * Automattic
   * Hosts (WP Engine, Kinsta)
   * Seravo

---

## 10. Summary

BSM:

* Solves Gutenberg's biggest architectural problem
* Enables modern React-style development for blocks
* Reduces REST load
* Creates the foundation for WordPress's future UI development

---

## License

MIT
