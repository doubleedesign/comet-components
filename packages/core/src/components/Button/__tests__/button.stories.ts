import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type ButtonProps = {
	tagName: 'a' | 'button';
	colorTheme: ThemeColor;
	isOutline: boolean;
	href?: string;
	classes?: string[];
}

const meta = {
	title: 'Text/Button',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Button'),
	args: {
		tagName: "a",
		colorTheme: "primary",
		isOutline: false,
		href: "#",

	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "a"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"a",
				"button"
			]
		},
		colorTheme: {
			description: "Colour keyword for the fill or outline colour",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "primary"
				},
				type: {
					summary: "ThemeColor"
				}
			},
			options: THEME_COLORS
		},
		isOutline: {
			description: "Whether to use outline style instead of solid/filled",
			control: {
				type: "boolean"
			},
			table: {
				defaultValue: {
					summary: "false"
				},
				type: {
					summary: "bool"
				}
			}
		},
		href: {
			description: "URL to link to if using <a> tag.",
			control: false,
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
					summary: "button button--primary"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<ButtonProps>;

export default meta;
type Story = StoryObj<ButtonProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
