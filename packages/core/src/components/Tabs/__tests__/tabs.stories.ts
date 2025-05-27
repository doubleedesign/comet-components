import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Orientation, ORIENTATION_OPTIONS, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type TabsProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	orientation: Orientation;
	classes?: string[];
}

const meta = {
	title: 'Layout/Tabs',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...createStoryBase('Tabs'),
	args: {
		tagName: "div",
		colorTheme: "primary",
		orientation: "vertical"
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
					summary: "vertical"
				},
				type: {
					summary: "Orientation"
				}
			},
			options: ORIENTATION_OPTIONS
		},
		colorTheme: {
			description: "Colour keyword for the fill or outline colour",
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
			options: THEME_COLORS
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "tabs"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<TabsProps>;

export default meta;
type Story = StoryObj<TabsProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
