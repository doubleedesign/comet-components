import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type DateBlockProps = {
	tagName: 'time';
	date: string;
	locale: string;
	showDay: boolean;
	showYear: boolean;
	classes?: string[];
}

const meta = {
	title: 'Text/DateBlock',
	tags: ['autodocs'],
	...createStoryBase('DateBlock'),
	args: {
		date: "2025-06-14",
		locale: "en_AU",
		showDay: false,
		showYear: true
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "time"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"time"
			]
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "date-block"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		date: {
			description: "The date to be displayed; can be passed in via $attributes as either as a DateTime object, Unix timestamp, or a string in YYYY-MM-DD format.",
			control: {
				type: "date"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "DateTime"
				}
			}
		},
		locale: {
			description: "The locale to be used for formatting the date.",
			control: false,
			table: {
				defaultValue: {
					summary: "en_AU"
				},
				type: {
					summary: "string"
				}
			}
		},
		showDay: {
			description: "Whether to show the day of the week.",
			control: {
				type: "boolean"
			},
			table: {
				defaultValue: {
					summary: "false"
				},
				type: {
					summary: "bool"
				}
			}
		},
		showYear: {
			description: "Whether to show the year.",
			control: {
				type: "boolean"
			},
			table: {
				defaultValue: {
					summary: "true"
				},
				type: {
					summary: "bool"
				}
			}
		}
	},
} satisfies Meta<DateBlockProps>;

export default meta;
type Story = StoryObj<DateBlockProps>;

export const Playground: Story = {
	tags: []
};
