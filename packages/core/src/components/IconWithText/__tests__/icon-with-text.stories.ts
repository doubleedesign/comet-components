import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type IconWithTextProps = {
	tagName: 'div';
	label: string;
	iconPrefix: string;
	icon: string;
	colorTheme: ThemeColor;
	classes?: string[];
}

const meta = {
	title: 'Text/IconWithText',
	tags: ['autodocs'],
	...createStoryBase('IconWithText'),
	args: {
		tagName: "div",
		label: "",
		iconPrefix: "fa-solid",
		icon: "fa-circle-check",
		colorTheme: "primary"
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
					summary: "primary"
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
			description: "Icon class name",
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
					summary: "icon-with-text"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		label: {
			description: "",
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
		}
	},
} satisfies Meta<IconWithTextProps>;

export default meta;
type Story = StoryObj<IconWithTextProps>;

export const Playground: Story = {
	tags: []
};
