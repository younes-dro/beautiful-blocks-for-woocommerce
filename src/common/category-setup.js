
import { updateCategory } from '@wordpress/blocks';
import { BeautifulBlocksIcon } from './assets/beautiful-blocks-icon';


export const setupBeautifulBlocksCategory = () => {
	updateCategory('beautiful-blocks', {
		icon: <BeautifulBlocksIcon />
	});
};


setupBeautifulBlocksCategory();
