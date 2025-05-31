import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type HeadingProps = {
	level: 1 | 2 | 3 | 4 | 5 | 6;
	textAlign: '' | Alignment;
	textColor: ThemeColor
	classes: '' | 'is-style-accent' | 'is-style-small';

}

const meta = {
	title: 'Text/Heading',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Heading'),
	args: {
		level: 2,
		textAlign: "",
		textColor: "",
		classes: ""
	},
	argTypes: {
		classes: {
			description: "CSS classes",
			control: {
				type: "select"
			},
			options: [
				"",
				"is-style-accent",
				"is-style-small"
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
		level: {
			description: "Heading level (1-6). Default is 2. Cannot be used in conjunction with tagName.",
			control: {
				type: "select"
			},
			options: [
				1,
				2,
				3,
				4,
				5,
				6
			],
			table: {
				defaultValue: {
					summary: "2"
				},
				type: {
					summary: "int"
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
} satisfies Meta<HeadingProps>;

export default meta;
type Story = StoryObj<HeadingProps>;

export const Playground: Story = {
	tags: []
};
