import React from 'react';

import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useState, useEffect } from "@wordpress/element";

import '../styles/editor.css';


const Edit = (props) => {
	
	function setGitUser(e) {
		props.setAttributes({ githubuser: e.target.value })
	} 

	return (
		<div {...useBlockProps()}>
			<div className="setUpYourGitHubURL">
				<h2 className='color'>Github User</h2>
				<input type="text" 
					value={props.attributes.githubuser} 
					onChange={setGitUser} 
					placeholder="Type your github user here" />
			</div>
		
		</div>
	);
}

export default Edit;
