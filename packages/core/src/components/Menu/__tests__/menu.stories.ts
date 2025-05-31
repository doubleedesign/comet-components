import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type MenuProps = {
	tagName: 'nav';
	classes?: string[];

}

const meta = {
	title: 'Navigation/Menu',
	tags: ['wordpress-theme', 'autodocs'],
	...createStoryBase('Menu'),
	args: {
		tagName: "nav",

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
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "menu"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<MenuProps>;

export default meta;
type Story = StoryObj<MenuProps>;

export const Playground: Story = {
	tags: []
};
