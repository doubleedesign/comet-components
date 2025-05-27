import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";

type GalleryProps = {
	tagName: 'figure' | 'div';
	caption: string;
	columns: 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8;
	classes?: string[];
}

const meta = {
	title: 'Media/Gallery',
	tags: ['wordpress-block', 'javascript', 'autodocs'],
	...createStoryBase('Gallery'),
	args: {
		tagName: "figure",
		caption: "",
		columns: 3
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "figure"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"figure",
				"div"
			]
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "gallery"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		caption: {
			description: "Caption describing the whole gallery; supports inline phrasing HTML tags such as <em> and <strong>",
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
		columns: {
			description: "The number of columns to use for the layout (may be overridden to fewer in small containers/viewports)",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "3"
				},
				type: {
					summary: "int"
				}
			},
			options: [
				"1",
				"2",
				"3",
				"4",
				"5",
				"6",
				"7",
				"8"
			]
		}
	},
} satisfies Meta<GalleryProps>;

export default meta;
type Story = StoryObj<GalleryProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
