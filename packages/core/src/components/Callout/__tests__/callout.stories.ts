import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type CalloutProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	iconPrefix: string;
	icon: string;
	classes?: string[];
}

const meta = {
	title: 'UI/Callout',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Callout'),
	args: {
		tagName: "div",
		colorTheme: "info",
		iconPrefix: "fa-solid",
		icon: "",

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
		colorTheme: {
			description: "Colour keyword for the fill or outline colour",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "info"
				},
				type: {
					summary: "ThemeColor"
				}
			},
			options: THEME_COLORS
		},
		iconPrefix: {
			description: "Icon prefix class name",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "fa-solid"
				},
				type: {
					summary: "string"
				}
			}
		},
		icon: {
			description: "Icon class name; default value set for success, warning, error, and info color themes",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: ""
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
					summary: "callout callout--info"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<CalloutProps>;

export default meta;
type Story = StoryObj<CalloutProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
