import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type SeparatorProps = {
	tagName: 'hr';
	color: ThemeColor;
	classes?: string[];
}

const meta = {
	title: 'UI/Separator',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Separator'),
	args: {
		tagName: "hr",
		color: "primary"
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "hr"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"hr"
			]
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "separator"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		color: {
			description: "",
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
		}
	},
} satisfies Meta<SeparatorProps>;

export default meta;
type Story = StoryObj<SeparatorProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
