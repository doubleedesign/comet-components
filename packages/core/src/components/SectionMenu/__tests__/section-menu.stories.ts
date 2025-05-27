import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type SectionMenuProps = {
	tagName: 'nav';
	colorTheme: ThemeColor;
	classes?: string[];
}

const meta = {
	title: 'Navigation/SectionMenu',
	tags: ['wordpress-theme', 'autodocs'],
	...createStoryBase('SectionMenu'),
	args: {
		tagName: "nav",
		colorTheme: "primary",

	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "nav"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"nav"
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
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "section-navigation"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<SectionMenuProps>;

export default meta;
type Story = StoryObj<SectionMenuProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
