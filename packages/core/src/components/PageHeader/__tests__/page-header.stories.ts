import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ContainerSize, CONTAINER_SIZES, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type PageHeaderProps = {
	tagName: 'header' | 'div' | 'section';
	size: ContainerSize;
	backgroundColor: '' | ThemeColor;
	classes?: string[];
}

const meta = {
	title: 'UI/PageHeader',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('PageHeader'),
	args: {
		tagName: "header",
		size: "default",
		backgroundColor: "",

	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "header"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"header",
				"div",
				"section"
			]
		},
		size: {
			description: "Keyword specifying the relative width of the container for the inner content",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "$this->breadcrumbs"
				},
				type: {
					summary: "ContainerSize"
				}
			},
			options: CONTAINER_SIZES
		},
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
			options: THEME_COLORS
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "page-section page-header"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<PageHeaderProps>;

export default meta;
type Story = StoryObj<PageHeaderProps>;

export const Playground: Story = {
	tags: []
};
