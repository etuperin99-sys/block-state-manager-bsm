<?php
/**
 * BSM Admin Page
 */

defined('ABSPATH') || exit;
?>

<div class="bsm-admin-wrap">
    <!-- Header -->
    <div class="bsm-header">
        <h1>Block State Manager (BSM)</h1>
        <p>React-style global state management for Gutenberg blocks</p>
        <span class="bsm-version">Version <?php echo esc_html(BSM_VERSION); ?></span>
    </div>

    <!-- Navigation Tabs -->
    <div class="bsm-tabs">
        <button class="bsm-tab active" data-tab="overview">Overview</button>
        <button class="bsm-tab" data-tab="getting-started">Getting Started</button>
        <button class="bsm-tab" data-tab="api">API Reference</button>
        <button class="bsm-tab" data-tab="examples">Examples</button>
        <button class="bsm-tab" data-tab="demo">Demo Blocks</button>
    </div>

    <!-- Overview Tab -->
    <div class="bsm-tab-content active" id="tab-overview">
        <div class="bsm-grid">
            <div class="bsm-card">
                <h2>What is BSM?</h2>
                <p>BSM (Block State Manager) is a developer tool that brings modern React-style state management to WordPress Gutenberg blocks.</p>
                <p>It solves the fundamental problem of <strong>cross-block communication</strong> - allowing different blocks on a page to share and react to the same state without REST API calls or complex event systems.</p>

                <div class="bsm-info">
                    <div class="bsm-info-title">Key Concept</div>
                    <p>Think of BSM as Redux/Context for Gutenberg. Multiple blocks can read and write to the same global state, and all blocks automatically update when that state changes.</p>
                </div>
            </div>

            <div class="bsm-card">
                <h2>Features</h2>
                <ul class="bsm-features">
                    <li>
                        <span class="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>Global State</strong><br>
                            <span>One shared state accessible by all blocks</span>
                        </div>
                    </li>
                    <li>
                        <span class="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>React Hooks API</strong><br>
                            <span>Familiar useState-like interface</span>
                        </div>
                    </li>
                    <li>
                        <span class="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>Editor + Frontend</strong><br>
                            <span>Works in both Gutenberg editor and published pages</span>
                        </div>
                    </li>
                    <li>
                        <span class="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>No REST API Needed</strong><br>
                            <span>State syncs instantly without server calls</span>
                        </div>
                    </li>
                    <li>
                        <span class="dashicons dashicons-yes-alt"></span>
                        <div>
                            <strong>WordPress Data API</strong><br>
                            <span>Built on wp.data for seamless integration</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bsm-card">
            <h2>The Problem BSM Solves</h2>
            <div class="bsm-grid">
                <div>
                    <h3>Without BSM</h3>
                    <ul>
                        <li>Blocks are isolated - they can't communicate</li>
                        <li>Cross-block data requires REST API calls</li>
                        <li>Complex custom event systems needed</li>
                        <li>Prop-drilling through block hierarchy</li>
                        <li>No reactive updates between blocks</li>
                    </ul>
                </div>
                <div>
                    <h3>With BSM</h3>
                    <ul>
                        <li>All blocks share one global state</li>
                        <li>Instant state sync - no API calls</li>
                        <li>Simple hook-based API</li>
                        <li>Any block can read/write any state</li>
                        <li>Automatic reactive updates everywhere</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Getting Started Tab -->
    <div class="bsm-tab-content" id="tab-getting-started">
        <div class="bsm-card">
            <h2>Quick Start</h2>
            <p>BSM is a developer library. To use it, you need to import the hook in your custom Gutenberg block:</p>

            <h3>1. Import the Hook</h3>
            <div class="bsm-code">
                <div class="bsm-code-header">your-block/edit.js</div>
                <pre><code class="language-jsx">import { useBlockState } from 'bsm-store';

// Or import specific hooks
import { useBlockState, useBlockStateValue, useBlockStates } from 'bsm-store';</code></pre>
            </div>

            <h3>2. Use in Your Block</h3>
            <div class="bsm-code">
                <div class="bsm-code-header">your-block/edit.js</div>
                <pre><code class="language-jsx">const Edit = () => {
    // Similar to useState, but global!
    const [count, setCount] = useBlockState('myapp/counter', 0);

    return (
        &lt;div&gt;
            &lt;p&gt;Count: {count}&lt;/p&gt;
            &lt;button onClick={() => setCount(count + 1)}&gt;
                Increment
            &lt;/button&gt;
        &lt;/div&gt;
    );
};</code></pre>
            </div>

            <h3>3. Access Same State From Another Block</h3>
            <div class="bsm-code">
                <div class="bsm-code-header">another-block/edit.js</div>
                <pre><code class="language-jsx">const AnotherBlockEdit = () => {
    // Same key = same state!
    const [count, setCount] = useBlockState('myapp/counter', 0);

    // This block sees the same count value
    // and can also update it
    return &lt;p&gt;The count is: {count}&lt;/p&gt;;
};</code></pre>
            </div>

            <div class="bsm-info bsm-success">
                <div class="bsm-info-title">That's it!</div>
                <p>Any block using the same state key will automatically stay in sync. When one block updates the state, all other blocks see the change immediately.</p>
            </div>
        </div>

        <div class="bsm-card">
            <h2>State Key Convention</h2>
            <p>Use namespaced keys to organize your state and avoid conflicts:</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">// Good - namespaced keys
useBlockState('shop/cart', [])
useBlockState('shop/user', {})
useBlockState('realestate/filters', {})
useBlockState('gallery/activeIndex', 0)

// Avoid - generic keys that might conflict
useBlockState('data', {})
useBlockState('value', '')</code></pre>
            </div>
        </div>

        <div class="bsm-card">
            <h2>Add BSM as Dependency</h2>
            <p>Make sure your block depends on the BSM store script:</p>

            <div class="bsm-code">
                <div class="bsm-code-header">your-block/block.json</div>
                <pre><code class="language-jsx">{
    "name": "myplugin/my-block",
    "editorScript": "file:./index.js",
    "editorDependencies": ["bsm-store"]
}</code></pre>
            </div>

            <p>Or register the dependency in PHP:</p>

            <div class="bsm-code">
                <div class="bsm-code-header">your-plugin.php</div>
                <pre><code class="language-php">wp_register_script(
    'my-block-editor',
    plugins_url('build/index.js', __FILE__),
    ['bsm-store', 'wp-blocks', 'wp-element'], // Add bsm-store
    '1.0.0'
);</code></pre>
            </div>
        </div>
    </div>

    <!-- API Reference Tab -->
    <div class="bsm-tab-content" id="tab-api">
        <div class="bsm-card">
            <h2>Hooks</h2>

            <h3><code>useBlockState(key, initialValue)</code></h3>
            <p>The primary hook for reading and writing state. Similar to React's useState but global.</p>

            <table class="bsm-api-table">
                <tr>
                    <th>Parameter</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td><code>key</code></td>
                    <td>string</td>
                    <td>Unique identifier for this state (use namespace/name format)</td>
                </tr>
                <tr>
                    <td><code>initialValue</code></td>
                    <td>any</td>
                    <td>Default value if state doesn't exist</td>
                </tr>
            </table>

            <p><strong>Returns:</strong> <code>[value, setValue, helpers]</code></p>

            <div class="bsm-code">
                <pre><code class="language-jsx">const [user, setUser, helpers] = useBlockState('app/user', { name: '' });

// Read value
console.log(user.name);

// Set value
setUser({ name: 'John' });

// Functional update (like useState)
setUser(prev => ({ ...prev, name: 'Jane' }));

// Helpers
helpers.reset();        // Reset to initial value
helpers.remove();       // Delete this key from state
helpers.update({ name: 'Bob' }); // Partial object update
helpers.isInitial;      // Boolean: is value still initial?</code></pre>
            </div>

            <hr style="margin: 40px 0; border: none; border-top: 1px solid #eee;">

            <h3><code>useBlockStateValue(key, defaultValue)</code></h3>
            <p>Read-only hook for when you only need to observe state, not modify it.</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">// Just reading, no setter returned
const count = useBlockStateValue('app/counter', 0);</code></pre>
            </div>

            <hr style="margin: 40px 0; border: none; border-top: 1px solid #eee;">

            <h3><code>useBlockStates(keysWithDefaults)</code></h3>
            <p>Read multiple state values at once.</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">const state = useBlockStates({
    'form/name': '',
    'form/email': '',
    'form/step': 0
});

// Access values
console.log(state['form/name']);
console.log(state['form/email']);</code></pre>
            </div>

            <hr style="margin: 40px 0; border: none; border-top: 1px solid #eee;">

            <h3><code>useBlockStateDispatch()</code></h3>
            <p>Get direct access to dispatch functions.</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">const { setState, setMultiple, deleteKey, resetState } = useBlockStateDispatch();

setState('app/counter', 5);
setMultiple({ 'app/a': 1, 'app/b': 2 });
deleteKey('app/counter');
resetState();</code></pre>
            </div>
        </div>

        <div class="bsm-card">
            <h2>Store Direct Access</h2>
            <p>For advanced use cases, you can access the store directly:</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">import { bsmSelect, bsmDispatch, bsmSubscribe, STORE_NAME } from 'bsm-store';

// Read state
const value = bsmSelect().getState('app/counter');

// Write state
bsmDispatch().setState('app/counter', 10);

// Subscribe to all changes
const unsubscribe = bsmSubscribe(() => {
    console.log('State changed!');
});

// Store name for wp.data
// STORE_NAME === 'bsm/store'</code></pre>
            </div>
        </div>

        <div class="bsm-card">
            <h2>PHP Functions</h2>

            <h3><code>bsm_set_initial_state($key, $value)</code></h3>
            <p>Set initial state from PHP (useful for SSR/hydration).</p>

            <div class="bsm-code">
                <pre><code class="language-php">// In your theme or plugin
bsm_set_initial_state('shop/cart', [
    'items' => get_user_cart_items(),
    'total' => get_cart_total()
]);</code></pre>
            </div>

            <h3><code>bsm_get_state($key, $default)</code></h3>
            <p>Get state value in PHP (requires state to be passed back to server).</p>
        </div>
    </div>

    <!-- Examples Tab -->
    <div class="bsm-tab-content" id="tab-examples">
        <div class="bsm-card">
            <h2>Real-World Examples</h2>

            <h3>Shopping Cart</h3>
            <p>Product listing and cart blocks sharing state:</p>

            <div class="bsm-code">
                <div class="bsm-code-header">product-block/edit.js</div>
                <pre><code class="language-jsx">const ProductBlock = ({ product }) => {
    const [cart, setCart] = useBlockState('shop/cart', []);

    const addToCart = () => {
        setCart(prev => [...prev, product]);
    };

    return (
        &lt;div className="product"&gt;
            &lt;h3&gt;{product.name}&lt;/h3&gt;
            &lt;p&gt;${product.price}&lt;/p&gt;
            &lt;button onClick={addToCart}&gt;Add to Cart&lt;/button&gt;
        &lt;/div&gt;
    );
};</code></pre>
            </div>

            <div class="bsm-code">
                <div class="bsm-code-header">cart-block/edit.js</div>
                <pre><code class="language-jsx">const CartBlock = () => {
    const [cart, setCart] = useBlockState('shop/cart', []);

    const total = cart.reduce((sum, item) => sum + item.price, 0);

    return (
        &lt;div className="cart"&gt;
            &lt;h3&gt;Cart ({cart.length} items)&lt;/h3&gt;
            &lt;ul&gt;
                {cart.map(item => &lt;li&gt;{item.name}&lt;/li&gt;)}
            &lt;/ul&gt;
            &lt;p&gt;Total: ${total}&lt;/p&gt;
        &lt;/div&gt;
    );
};</code></pre>
            </div>
        </div>

        <div class="bsm-card">
            <h3>Search + Map + Listing</h3>
            <p>Three blocks working together:</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">// Search Block
const SearchBlock = () => {
    const [filters, setFilters] = useBlockState('realestate/filters', {
        query: '',
        minPrice: 0,
        maxPrice: 1000000
    });

    return (
        &lt;input
            value={filters.query}
            onChange={e => setFilters(f => ({...f, query: e.target.value}))}
            placeholder="Search properties..."
        /&gt;
    );
};

// Map Block - reads same state
const MapBlock = () => {
    const filters = useBlockStateValue('realestate/filters', {});
    // Render map markers filtered by `filters`
};

// Listing Block - reads same state
const ListingBlock = () => {
    const filters = useBlockStateValue('realestate/filters', {});
    // Render property list filtered by `filters`
};</code></pre>
            </div>
        </div>

        <div class="bsm-card">
            <h3>Multi-Step Form</h3>
            <p>Form steps sharing collected data:</p>

            <div class="bsm-code">
                <pre><code class="language-jsx">// All step blocks use the same state
const [formData, setFormData] = useBlockState('form/application', {
    step: 1,
    name: '',
    email: '',
    message: ''
});

// Step 1 Block
const Step1 = () => (
    &lt;div&gt;
        &lt;input
            value={formData.name}
            onChange={e => setFormData(d => ({...d, name: e.target.value}))}
        /&gt;
        &lt;button onClick={() => setFormData(d => ({...d, step: 2}))}&gt;
            Next
        &lt;/button&gt;
    &lt;/div&gt;
);

// Step 2 Block - same state, different UI
// Summary Block - reads all collected data</code></pre>
            </div>
        </div>

        <div class="bsm-card">
            <h3>Gallery + Lightbox</h3>

            <div class="bsm-code">
                <pre><code class="language-jsx">// Gallery Block
const GalleryBlock = ({ images }) => {
    const [, setLightbox] = useBlockState('gallery/lightbox', {
        open: false,
        index: 0
    });

    return (
        &lt;div className="gallery"&gt;
            {images.map((img, i) => (
                &lt;img
                    key={i}
                    src={img.thumbnail}
                    onClick={() => setLightbox({ open: true, index: i })}
                /&gt;
            ))}
        &lt;/div&gt;
    );
};

// Lightbox Block - separate block, same state
const LightboxBlock = ({ images }) => {
    const [lightbox, setLightbox] = useBlockState('gallery/lightbox', {
        open: false,
        index: 0
    });

    if (!lightbox.open) return null;

    return (
        &lt;div className="lightbox"&gt;
            &lt;img src={images[lightbox.index].full} /&gt;
            &lt;button onClick={() => setLightbox(l => ({...l, open: false}))}&gt;
                Close
            &lt;/button&gt;
        &lt;/div&gt;
    );
};</code></pre>
            </div>
        </div>
    </div>

    <!-- Demo Blocks Tab -->
    <div class="bsm-tab-content" id="tab-demo">
        <div class="bsm-card">
            <h2>Demo Blocks</h2>
            <p>BSM includes three demo blocks to demonstrate the shared state functionality:</p>

            <div class="bsm-grid">
                <div>
                    <h3>BSM Counter</h3>
                    <p>Add multiple counter blocks to a page. They all share the same count value - clicking + or - in one updates all of them instantly.</p>
                </div>
                <div>
                    <h3>BSM Shared Input + Display</h3>
                    <p>Add a Shared Input block and multiple Display blocks. Whatever you type in the input appears in all Display blocks in real-time.</p>
                </div>
            </div>

            <div class="bsm-demo-blocks">
                <p>Try the demo blocks in the Gutenberg editor:</p>
                <a href="<?php echo esc_url(admin_url('post-new.php')); ?>" class="bsm-btn">
                    Create New Post
                </a>
                <p style="margin-top: 15px; font-size: 13px; color: #666;">
                    Search for "BSM" in the block inserter to find the demo blocks.
                </p>
            </div>
        </div>

        <div class="bsm-card">
            <h2>Demo Block Source Code</h2>
            <p>The demo blocks are included in the plugin source code as reference implementations:</p>

            <div class="bsm-code">
                <div class="bsm-code-header">Location</div>
                <pre><code>plugins/block-state-manager/src/blocks/counter/
plugins/block-state-manager/src/blocks/shared-input/
plugins/block-state-manager/src/blocks/display/</code></pre>
            </div>

            <p>Study these blocks to understand how to implement BSM in your own blocks.</p>
        </div>

        <div class="bsm-card">
            <h2>Resources</h2>
            <div class="bsm-links">
                <a href="https://github.com/etuperin99-sys/block-state-manager-bsm" target="_blank">
                    <span class="dashicons dashicons-github"></span> GitHub Repository
                </a>
                <a href="https://github.com/etuperin99-sys/block-state-manager-bsm#readme" target="_blank">
                    <span class="dashicons dashicons-book"></span> Documentation
                </a>
                <a href="https://github.com/etuperin99-sys/block-state-manager-bsm/issues" target="_blank">
                    <span class="dashicons dashicons-warning"></span> Report Issues
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.bsm-tab');
    const contents = document.querySelectorAll('.bsm-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active from all
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            // Add active to clicked
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });
});
</script>
