import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type BreadcrumbsProps = {
	tagName: 'nav';
	classes?: string[];
}

const meta = {
	title: 'Navigation/Breadcrumbs',
	tags: ['wordpress-theme', 'autodocs'],
	...createStoryBase('Breadcrumbs'),
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
					summary: "breadcrumbs"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<BreadcrumbsProps>;

export default meta;
type Story = StoryObj<BreadcrumbsProps>;

export const Playground: Story = {
	tags: []
};
