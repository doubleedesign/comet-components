import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import {
	Alignment,
	Orientation,
	ORIENTATION_OPTIONS,
	THEME_COLORS,
	ThemeColor
} from "../../../../test/storybook-helpers.ts";

type StepsProps = {
	tagName: 'ol';
	orientation: Orientation;
	hAlign: Alignment;
	vAlign: Alignment;
	colorTheme: ThemeColor
	backgroundColor: ThemeColor
	maxPerRow: string;
	classes?: '' | 'is-style-numbered' | 'is-style-simple';

}

const meta = {
	title: 'UI/Steps',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Steps'),
	args: {
		tagName: "ol",
		orientation: "vertical",
		hAlign: "start",
		vAlign: "start",
		colorTheme: "",
		backgroundColor: "",
		maxPerRow: ""
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "ol"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"ol"
			]
		},
		orientation: {
			description: "Orientation of the component content, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "vertical"
				},
				type: {
					summary: "Orientation"
				}
			},
			options: ORIENTATION_OPTIONS
		},
		hAlign: {
			description: "Horizontal alignment, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "start"
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
		vAlign: {
			description: "Vertical alignment, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "start"
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
			control: {
				type: "select"
			},
			options: [
				"",
				"is-style-numbered",
				"is-style-simple"
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
		maxPerRow: {
			description: "The maximum number of steps to display per row when orientation is horizontal",
			control: false,
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "int"
				}
			}
		}
	},
} satisfies Meta<StepsProps>;

export default meta;
type Story = StoryObj<StepsProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
