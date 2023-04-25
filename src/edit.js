import React from 'react';

import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

import './styles/editor.css';


const Edit = ({ attributes, setAttributes }) => {

	const { githubuser } = attributes;

	return (
		<div {...useBlockProps()}>
			<div className="edit">
				<h3>Github User </h3>

				<p>
					Just set up your github username below, and the block will easily display your public 
					Github repositories.
				</p>

				<RichText
					value={ githubuser }
					onChange={( githubuser ) => setAttributes( { githubuser } )}
					placeholder="Type your github user here" 
					/>
			</div>
		
		</div>
	);
}

export default Edit;
