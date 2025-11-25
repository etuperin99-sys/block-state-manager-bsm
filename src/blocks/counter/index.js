/**
 * BSM Counter Block
 *
 * Demonstrates shared state between multiple block instances.
 * All counter blocks on the page share the same count value.
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Button, Card, CardBody } from '@wordpress/components';
import { useBlockState } from '../../hooks';

import metadata from './block.json';
import './editor.css';

/**
 * Edit component
 */
const Edit = () => {
    const blockProps = useBlockProps();

    // All counter blocks share this state!
    const [count, setCount] = useBlockState('bsm/counter', 0);

    return (
        <div {...blockProps}>
            <Card>
                <CardBody>
                    <div className="bsm-counter">
                        <h3 className="bsm-counter__title">BSM Shared Counter</h3>
                        <p className="bsm-counter__description">
                            This counter shares state with all other counter blocks on the page.
                        </p>
                        <div className="bsm-counter__display">
                            <span className="bsm-counter__value">{count}</span>
                        </div>
                        <div className="bsm-counter__buttons">
                            <Button
                                variant="secondary"
                                onClick={() => setCount((prev) => prev - 1)}
                            >
                                -
                            </Button>
                            <Button
                                variant="secondary"
                                onClick={() => setCount(0)}
                            >
                                Reset
                            </Button>
                            <Button
                                variant="primary"
                                onClick={() => setCount((prev) => prev + 1)}
                            >
                                +
                            </Button>
                        </div>
                    </div>
                </CardBody>
            </Card>
        </div>
    );
};

/**
 * Register block
 */
registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null, // Dynamic block - rendered via PHP
});
