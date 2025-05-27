import type { Meta, StoryObj } from '@storybook/html';
import { type Orientation, type ThemeColor, THEME_COLORS, ORIENTATION_OPTIONS } from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

type AccordionProps = {
	colorTheme: ThemeColor;
	icon: string;
	iconPrefix: string;
	orientation: Orientation;
	tagName: 'div';
	classes?: string[];
}

const meta = {
	title: 'Layout/Accordion',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...createStoryBase('Accordion'),
	args: {
		colorTheme: "primary",
		icon: "fa-plus",
		iconPrefix: "fa-solid",
		orientation: "vertical",
		tagName: "div",

	},
	argTypes: {
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "accordion"
				},
				type: {
					summary: "array<string>"
				}
			}
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
		icon: {
			description: "Icon class name for the icon to use for the expand/collapse indicator.",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "fa-plus"
				},
				type: {
					summary: "string"
				}
			}
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
		orientation: {
			description: "",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "vertical"
				},
				type: {
					summary: "Orientation"
				}
			},
			options: ORIENTATION_OPTIONS
		},
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
				"div"
			]
		},
	},
} satisfies Meta<AccordionProps>;

export default meta;
type Story = StoryObj<AccordionProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
