import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, Orientation, ORIENTATION_OPTIONS } from '../../../../test/storybook-helpers.ts';

type ButtonGroupProps = {
	tagName: 'div';
	orientation: Orientation;
	hAlign: Alignment;
	vAlign: Alignment;
	classes?: string[];
}

const meta = {
	title: 'Text/ButtonGroup',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('ButtonGroup'),
	args: {
		tagName: "div",
		orientation: "horizontal",
		hAlign: "start",
		vAlign: "start",

	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "div"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"div"
			]
		},
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
			options: ORIENTATION_OPTIONS
		},
		hAlign: {
			description: "Horizontal alignment, if applicable",
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
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "button-group"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<ButtonGroupProps>;

export default meta;
type Story = StoryObj<ButtonGroupProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
