import * as element from '@wordpress/element';
import * as components from '@wordpress/components';
import * as blockEditor from '@wordpress/block-editor';
import * as blocks from '@wordpress/blocks';
import * as hooks from '@wordpress/hooks';

export const ASPECT_RATIOS = [
	{ name: 'STANDARD',         value: '4:3'    },
	{ name: 'PORTRAIT',         value: '3:4'    },
	{ name: 'SQUARE',           value: '1:1'    },
	{ name: 'WIDE',             value: '16:9'   },
	{ name: 'TALL',             value: '9:16'   },
	{ name: 'CLASSIC',          value: '3:2'    },
	{ name: 'CLASSIC_PORTRAIT', value: '2:3'    },
	{ name: 'CINEMATIC',        value: '21:9'   },
	{ name: 'CINEMASCOPE',      value: '2.35:1' },
];

export const CONTAINER_SIZES = [
	{ label: 'Full-width', value: 'full' },
	{ label: 'Wide', value: 'wide' },
	{ label: 'Contained', value: 'contained' },
	{ label: 'Narrow', value: 'narrow' },
];

export const MOCK_PALETTE = [
	{ name: 'Primary', slug: 'primary', color: 'var(--color-primary)' },
	{ name: 'Secondary', slug: 'secondary', color: 'var(--color-secondary)' },
	{ name: 'Accent', slug: 'accent', color: 'var(--color-accent)' },
	{ name: 'Light', slug: 'light', color: 'var(--color-light)' },
	{ name: 'Dark', slug: 'dark', color: 'var(--color-dark)' },
	{ name: 'Info', slug: 'info', color: 'var(--color-info)' },
	{ name: 'Success', slug: 'success', color: 'var(--color-success)' },
	{ name: 'Warning', slug: 'warning', color: 'var(--color-warning)' },
	{ name: 'Error', slug: 'error', color: 'var(--color-error' },
];

export const MOCK_GRADIENTS = [
	{ name: 'white-dark', slug: 'white-dark', gradient: 'var(--gradient-white-dark)' },
	{ name: 'dark-white', slug: 'dark-white', gradient: 'var(--gradient-dark-white)' },
	{ name: 'white-primary', slug: 'white-primary', gradient: 'var(--gradient-white-primary)' },
	{ name: 'primary-white', slug: 'primary-white', gradient: 'var(--gradient-primary-white)' },
];

function mockCometConfig() {
	const mockConfig = {
		globalBackground: 'white',
		aspectRatios: ASPECT_RATIOS,
		palette: MOCK_PALETTE.reduce((acc, color) => {
			acc[color.slug] = color.color;

			return acc;
		}, {}),
		colourPairs: [
			{ foreground: 'primary', background: 'white' },
			{ foreground: 'secondary', background: 'white' },
			{ foreground: 'accent', background: 'white' },
			{ foreground: 'primary', background: 'light' },
			{ foreground: 'light', background: 'dark' },
			{ foreground: 'accent', background: 'dark' },
		],
		colourPairOverrides: {},
		sectionBackgrounds: MOCK_GRADIENTS.reduce((acc, gradient) => {
			acc[gradient.slug] = gradient.gradient;

			return acc;
		}, {}),
		// Component-level defaults
		defaults: {},
		ajaxUrl: 'https://example.com/wp-admin/admin-ajax.php',
		nonce: 'mocked_nonce',
		context: {
			object_type: 'page',
			id: 123,
		}
	};

	return mockConfig;
}

window.wp = {
	blockEditor,
	element,
	components,
	blocks,
	hooks,
};

window.comet = mockCometConfig();
