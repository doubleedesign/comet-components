import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type DateRangeBlockProps = {
	tagName: 'time';
	colorTheme: ThemeColor;
	startDate: string;
	endDate: string;
	locale: string;
	showDay: boolean;
	showYear: boolean;
	classes?: string[];
}

const meta = {
	title: 'Text/DateRangeBlock',
	tags: ['autodocs'],
	...createStoryBase('DateRangeBlock'),
	args: {
		colorTheme: "primary",
		startDate: "2025-06-13",
		endDate: "2025-06-15",
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
					summary: "date-range-block"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		endDate: {
			description: "The end date to be displayed; can be passed in via $attributes as either as a DateTime object, Unix timestamp, or a string in YYYY-MM-DD format.",
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
			description: "Whether to show the days of the week.",
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
			description: "Whether to show the year(s).",
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
		},
		startDate: {
			description: "The start date to be displayed; can be passed in via $attributes as either as a DateTime object, Unix timestamp, or a string in YYYY-MM-DD format.",
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
		}
	},
} satisfies Meta<DateRangeBlockProps>;

export default meta;
type Story = StoryObj<DateRangeBlockProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
