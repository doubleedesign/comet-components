import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type LinkProps = {
	tagName: 'a';
	iconPrefix: string;
	icon: string;
	classes?: string[];
}

const meta = {
	title: 'Media/Link',
	tags: ['autodocs'],
	...createStoryBase('Link'),
	args: {
		tagName: "a",
		iconPrefix: "fa-solid",
		icon: "",

	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "a"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"a"
			]
		},
		iconPrefix: {
			description: "Icon prefix class name",
			control: false,
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
			description: "Icon class name; for link-group context default value is 'fa-link', or 'fa-arrow-up-right-from-square' if target is '_blank'",
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
					summary: "link"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<LinkProps>;

export default meta;
type Story = StoryObj<LinkProps>;

export const Playground: Story = {
	tags: []
};
