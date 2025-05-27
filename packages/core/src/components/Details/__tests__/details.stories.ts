import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";

type DetailsProps = {
	tagName: 'details';
	summary: string;
	classes?: string[];
}

const meta = {
	title: 'Text/Details',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Details'),
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "details"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"details"
			]
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "details"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		summary: {
			description: "The title of the panel, summarising the content.",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "string"
				}
			}
		}
	},
} satisfies Meta<DetailsProps>;

export default meta;
type Story = StoryObj<DetailsProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
