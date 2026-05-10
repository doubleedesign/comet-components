import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, ThemeColor, THEME_COLORS, ContainerSize, CONTAINER_SIZES } from '../../../../test/storybook-helpers.ts';

type ColumnsProps = {
	tagName: 'div' | 'section';
	allowStacking: boolean;
	hAlign: Alignment;
	vAlign: Alignment;
	backgroundColor: '' | 'none' | ThemeColor;
	size: ContainerSize,
	classes?: string[];
	innerComponents?: Array<{
		attributes: any[];
		content: string;
	}>;
}

type ColumnsStoryProps = ColumnsProps & {
	count: number;
	innerBackgrounds: boolean;
}

const meta = {
	title: 'Layout/Columns',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Columns'),
	args: {
		tagName: "div",
		allowStacking: true,
		hAlign: "start",
		vAlign: "start",
		backgroundColor: "",
		size: 'contained',
		count: 3,
		innerBackgrounds: false
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "div"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"div",
				"section"
			]
		},
		size: {
			description: "Keyword specifying the relative width of the container for the inner content",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "contained"
				},
				type: {
					summary: "ContainerSize"
				}
			},
			options: CONTAINER_SIZES
		},
		allowStacking: {
			description: "Whether to adapt the layout by stacking columns when the viewport or container is narrow",
			control: {
				type: "boolean"
			},
			table: {
				defaultValue: {
					summary: "true"
				},
				type: {
					summary: "bool"
				}
			}
		},
		hAlign: {
			description: "",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "start"
				},
				type: {
					summary: "Alignment"
				}
			},
			options: ALIGNMENT_OPTIONS
		},
		vAlign: {
			description: "",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "start"
				},
				type: {
					summary: "Alignment"
				}
			},
			options: ALIGNMENT_OPTIONS
		},
		backgroundColor: {
			description: "Background colour keyword",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "ThemeColor"
				}
			},
			options: ["none", ...THEME_COLORS]
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "columns"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		count: {
			description: 'How many columns to show in this demo; they will be semi-randomly assigned basic content of varying lengths.',
		},
		innerBackgrounds: {
			description: 'Inner columns will be semi-randomly assigned background colours (or lack thereof).'
		}
	},
} satisfies Meta<ColumnsStoryProps>;

export default meta;
type Story = StoryObj<ColumnsStoryProps>;

export const Playground: Story = {
	tags: []
};
