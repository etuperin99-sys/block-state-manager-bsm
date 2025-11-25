/**
 * BSM Shared Input Block
 *
 * Demonstrates cross-block communication.
 * Text entered here appears in all BSM Display blocks.
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, Card, CardBody } from '@wordpress/components';
import { useBlockState } from '../../hooks';

import metadata from './block.json';
import './editor.css';

/**
 * Edit component
 */
const Edit = () => {
    const blockProps = useBlockProps();

    // Shared state with Display blocks
    const [text, setText] = useBlockState('bsm/shared-text', '');

    return (
        <div {...blockProps}>
            <Card>
                <CardBody>
                    <div className="bsm-shared-input">
                        <h3 className="bsm-shared-input__title">BSM Shared Input</h3>
                        <p className="bsm-shared-input__description">
                            Type here and see the text appear in all BSM Display blocks!
                        </p>
                        <TextControl
                            value={text}
                            onChange={setText}
                            placeholder="Type something..."
                            className="bsm-shared-input__field"
                        />
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
