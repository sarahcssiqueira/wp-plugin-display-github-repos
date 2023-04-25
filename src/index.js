import './styles/style.css';
import { registerBlockType } from '@wordpress/blocks';

import Edit from './edit';
import metadata from '../block.json';

registerBlockType( 
	metadata, {
		edit: Edit,
		save: () => null,
	} 
)