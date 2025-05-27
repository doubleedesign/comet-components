import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type PullquoteProps = {
	tagName: 'blockquote';
	colorTheme: ThemeColor;
	citation: string;
	textAlign: '' | Alignment;
	textColor: ThemeColor
	classes?: string;
}

const meta = {
	title: 'Text/Pullquote',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Pullquote'),
	args: {
		tagName: "blockquote",
		colorTheme: "secondary",
		citation: "",
		textAlign: "",
		textColor: "",
		classes: ""
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "blockquote"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"blockquote"
			]
		},
		colorTheme: {
			description: "Colour keyword for the fill or outline colour",
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
					summary: "pullquote"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		citation: {
			description: "Optional citation for the quote",
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
} satisfies Meta<PullquoteProps>;

export default meta;
type Story = StoryObj<PullquoteProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
