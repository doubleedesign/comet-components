import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";

type TableProps = {
	tagName: 'table';
	allowStacking: boolean;
	caption: string | object;
	sticky: '' | 'header' | 'first-column';
	classes?: string[];
}

const meta = {
	title: 'Text/Table',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Table'),
	args: {
		tagName: "table",
		allowStacking: true,
		caption: "",
		sticky: ""
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "table"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"table"
			]
		},
		allowStacking: {
			description: "Whether to adapt the layout by stacking columns when the viewport or container is narrow",
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
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "table"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		caption: {
			description: "Caption object, or content and attributes corresponding to a Caption object",
			control: false,
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "TableCaption|array"
				}
			}
		},
		sticky: {
			description: "Make the header \"sticky\" when the table is large enough to scroll vertically, or make the first column \"sticky\" when the table is large enough to scroll horizontally; designed for use with <thead> or the first cells all being <th scope=\"row\"> elements",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "string"
				}
			},
			options: [
				"header",
				"first-column"
			]
		}
	},
} satisfies Meta<TableProps>;

export default meta;
type Story = StoryObj<TableProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
