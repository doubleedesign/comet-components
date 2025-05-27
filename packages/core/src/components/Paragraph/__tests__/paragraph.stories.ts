import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type ParagraphProps = {
	tagName: 'p';
	classes: '' | 'is-style-lead';
	textAlign: '' | Alignment;
	textColor: ThemeColor
}

const meta = {
	title: 'Text/Paragraph',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Paragraph'),
	args: {
		tagName: "p",
		classes: "",
		textAlign: "",
		textColor: ""
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "p"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"p"
			]
		},
		classes: {
			description: "CSS classes",
			control: {
				type: "select"
			},
			options: [
				"",
				"is-style-lead"
			],
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "string"
				}
			}
		},
		textAlign: {
			description: "",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "Alignment"
				}
			},
			options: [
				"start",
				"end",
				"center",
				"justify",
				"match-parent"
			]
		},
		textColor: {
			description: "",
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
		}
	},
} satisfies Meta<ParagraphProps>;

export default meta;
type Story = StoryObj<ParagraphProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
