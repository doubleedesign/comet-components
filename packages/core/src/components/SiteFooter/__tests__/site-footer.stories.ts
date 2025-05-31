import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type SiteFooterProps = {
	backgroundColor: '' | 'none' | 'primary' | 'secondary' | 'accent' | 'error' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'white';
	size: 'default' | 'wide' | 'fullwidth' | 'narrow' | 'narrower' | 'small';
	tagName: 'footer';
	classes?: string[];
}

const meta = {
	title: 'Layout/SiteFooter',
	tags: ['wordpress-theme', 'autodocs'],
	...createStoryBase('SiteFooter'),
	args: {
		backgroundColor: "",
		size: "default",
		tagName: "footer",
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
			options: [
				"none",
				"primary",
				"secondary",
				"accent",
				"error",
				"success",
				"warning",
				"info",
				"light",
				"dark",
				"white"
			]
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "site-footer"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		size: {
			description: "Keyword specifying the relative width of the container for the inner content",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "default"
				},
				type: {
					summary: "ContainerSize"
				}
			},
			options: [
				"default",
				"wide",
				"fullwidth",
				"narrow",
				"narrower",
				"small"
			]
		},
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "footer"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"footer"
			]
		},

	},
} satisfies Meta<SiteFooterProps>;

export default meta;
type Story = StoryObj<SiteFooterProps>;

export const Playground: Story = {
	tags: []
};
