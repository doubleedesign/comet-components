import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, Orientation, ORIENTATION_OPTIONS } from '../../../../test/storybook-helpers.ts';

type IconLinksProps = {
	tagName: 'div';
	orientation: Orientation;
	hAlign: Alignment;
	vAlign: Alignment;
	iconPrefix: string;
	classes?: string[];
}

const meta = {
	title: 'UI/IconLinks',
	tags: ['javascript', 'wordpress-theme', 'autodocs'],
	...createStoryBase('IconLinks'),
	args: {
		tagName: "div",
		orientation: "horizontal",
		hAlign: "center",
		vAlign: "start",
		iconPrefix: "fa-brands"
	},
	argTypes: {
		orientation: {
			description: "Orientation of the component content, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "horizontal"
				},
				type: {
					summary: "Orientation"
				}
			},
			options:ORIENTATION_OPTIONS
		},
		hAlign: {
			description: "Horizontal alignment, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "center"
				},
				type: {
					summary: "Alignment"
				}
			},
			options: ALIGNMENT_OPTIONS
		},
		vAlign: {
			description: "Vertical alignment, if applicable",
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
		iconPrefix: {
			description: "",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "fa-brands"
				},
				type: {
					summary: "string"
				}
			}
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "icon-links"
				},
				type: {
					summary: "array<string>"
				}
			}
		}
	},
} satisfies Meta<IconLinksProps>;

export default meta;
type Story = StoryObj<IconLinksProps>;

export const Playground: Story = {
	tags: []
};
