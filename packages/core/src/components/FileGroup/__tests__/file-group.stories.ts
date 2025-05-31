import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type FileGroupProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	classes?: string[];

}

const meta = {
	title: 'Media/FileGroup',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('FileGroup'),
	args: {
		tagName: "div",
		colorTheme: "primary",

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
					summary: "file-group"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<FileGroupProps>;

export default meta;
type Story = StoryObj<FileGroupProps>;

export const Playground: Story = {
	tags: []
};
