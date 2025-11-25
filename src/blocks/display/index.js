/**
 * BSM Display Block
 *
 * Reads and displays text from BSM Shared Input blocks.
 * Demonstrates reading shared state from another block type.
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Card, CardBody } from '@wordpress/components';
import { useBlockStateValue } from '../../hooks';

import metadata from './block.json';
import './editor.css';

/**
 * Edit component
 */
const Edit = () => {
    const blockProps = useBlockProps();

    // Read state from Shared Input blocks (read-only)
    const text = useBlockStateValue('bsm/shared-text', '');

    return (
        <div {...blockProps}>
            <Card>
                <CardBody>
                    <div className="bsm-display">
                        <h3 className="bsm-display__title">BSM Display</h3>
                        <p className="bsm-display__description">
                            This block displays text from BSM Shared Input blocks.
                        </p>
                        <div className="bsm-display__output">
                            {text || <span className="bsm-display__placeholder">Waiting for input...</span>}
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
    save: () => null,
});
