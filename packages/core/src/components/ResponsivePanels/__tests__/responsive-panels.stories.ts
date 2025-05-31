import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Orientation, ORIENTATION_OPTIONS, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type ResponsivePanelsProps = {
	breakpoint: string;
	colorTheme: ThemeColor;
	icon: string;
	iconPrefix: string;
	orientation: Orientation;
	tagName: 'div';
	classes?: string[];
}

const meta = {
	title: 'Layout/ResponsivePanels',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...createStoryBase('ResponsivePanels'),
	args: {
		breakpoint: "768px",
		colorTheme: "primary",
		icon: "",
		iconPrefix: "fa-solid",
		orientation: "vertical",
		tagName: "div",

	},
	argTypes: {
		breakpoint: {
			description: "The container breakpoint at which to switch between accordion and tabs",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "768px"
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
					summary: "responsive-panels"
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
			description: "Icon class name for the icon to use for the expand/collapse indicator in accordion mode",
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
} satisfies Meta<ResponsivePanelsProps>;

export default meta;
type Story = StoryObj<ResponsivePanelsProps>;

export const Playground: Story = {
	tags: []
};
