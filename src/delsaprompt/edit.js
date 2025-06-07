/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { useState } from '@wordpress/element';
import { TextareaControl, RangeControl, Button, PanelBody, SelectControl } from '@wordpress/components';
import { marked } from 'marked';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const {
		prompt,
		response
	} = attributes;

	const [temperature, setTemperature] = useState(0.7);
	const [maxTokens, setMaxTokens] = useState(256);
	const [topP, setTopP] = useState(1.0);
	const [loading, setLoading] = useState(false);

	const handleSubmit = async () => {
		setLoading(true);
		try {
			const res = await fetch('/wp-json/delsaprompt/v1/generate', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': delsapromptSettings.nonce,
				},
				body: JSON.stringify({
					prompt,
					temperature,
					max_tokens: maxTokens,
					top_p: topP,
					model: attributes.model,
				}),
			});
			const data = await res.json();
			setAttributes({ response: data.result || 'No result' });
		} catch (e) {
			setAttributes({ response: 'Error: ' + e.message });
		}
		setLoading(false);
	};

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<PanelBody title="OpenAI Settings" initialOpen={true}>
					<RangeControl
						label="Temperature"
						value={temperature}
						onChange={setTemperature}
						min={0}
						max={1}
						step={0.1}
					/>
					<RangeControl
						label="Max Tokens"
						value={maxTokens}
						onChange={setMaxTokens}
						min={1}
						max={4000}
						step={1}
					/>
					<RangeControl
						label="Top P"
						value={topP}
						onChange={setTopP}
						min={0}
						max={1}
						step={0.1}
					/>
					<SelectControl
						label="OpenAI Model"
						value={attributes.model}
						options={[
							{ label: 'GPT 3.5 Turbo', value: 'gpt-3.5-turbo' },
							{ label: 'GPT 4.1', value: 'gpt-4.1' },
							{ label: 'GPT 4.1 mini', value: 'gpt-4.1-mini' },
							{ label: 'GPT 4o', value: 'gpt-4o' }
						]}
						onChange={(model) => setAttributes({ model })}
					/>
				</PanelBody>
			</InspectorControls>

			<TextareaControl
				label="Prompt"
				value={prompt}
				onChange={(val) => setAttributes({ prompt: val })}
				rows={4}
			/>

			<Button
				variant="primary"
				onClick={handleSubmit}
				disabled={loading || !prompt}
				isBusy={loading}
			>
				Generate
			</Button>

			{response && (
				<div
					className="delsaprompt-response"
					style={{ marginTop: '1em' }}
					dangerouslySetInnerHTML={{ __html: marked.parse(response) }}
				/>
			)}

		</div>
	);
}
