import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type GroupProps = {
	backgroundColor: '' | 'none' | ThemeColor;
	tagName: 'div' | 'section' | 'article' | 'aside';
	classes?: string | string[];

}

const meta = {
	title: 'Structure/Group',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Group'),
	args: {
		backgroundColor: "",
		tagName: "div"
	},
	argTypes: {
		backgroundColor: {
			description: "Background colour keyword",
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
			options: ["none", ...THEME_COLORS]
		},
		classes: {
			description: "CSS classes",
			control: {
				type: "select"
			},
			options: [
				"",
				"group--breakout"
			],
			table: {
				defaultValue: {
					summary: "layout-block group"
				},
				type: {
					summary: "string"
				}
			}
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
				"div",
				"section",
				"article",
				"aside"
			]
		},
	},
} satisfies Meta<GroupProps>;

export default meta;
type Story = StoryObj<GroupProps>;

export const Playground: Story = {
	tags: []
};
