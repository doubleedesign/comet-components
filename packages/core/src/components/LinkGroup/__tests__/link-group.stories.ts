import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type LinkGroupProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	classes?: string[];

}

const meta = {
	title: 'Media/LinkGroup',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('LinkGroup'),
	args: {
		tagName: "div",
		colorTheme: "info",

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
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "link-group"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<LinkGroupProps>;

export default meta;
type Story = StoryObj<LinkGroupProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
