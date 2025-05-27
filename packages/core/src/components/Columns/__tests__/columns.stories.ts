import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type ColumnsProps = {
	tagName: 'div' | 'section';
	allowStacking: boolean;
	hAlign: Alignment;
	vAlign: Alignment;
	backgroundColor: '' | 'none' | ThemeColor;
	classes?: string[];
	innerComponents?: Array<{
		attributes: any[];
		content: string;
	}>;
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
					summary: "layout-block columns"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<ColumnsProps>;

export default meta;
type Story = StoryObj<ColumnsProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};

export const TwoEqualColumns: Story = {
	args: {
		innerComponents: [
			{
				attributes: [],
				content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris."
			},
			{
				attributes: [],
				content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris."
			}
		]
	}
};
