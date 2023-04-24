/* Style */
import '../styles/style.css';

/* WordPress Dependencies */
import { registerBlockType } from '@wordpress/blocks';


/* Imports */
import Edit from './edit';
import metadata from '../block.json';


registerBlockType( 
	metadata, {
		edit: Edit,
		save: () => null,
	} 
)