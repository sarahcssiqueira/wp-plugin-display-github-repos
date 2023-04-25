import React from 'react';

import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

import './styles/editor.css';


const Edit = ({ attributes, setAttributes }) => {

	const { githubuser } = attributes;

	return (
		<div {...useBlockProps()}>
			<div className="setUpYourGitHubURL">
				<h2 className='color'>Github User</h2>
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
