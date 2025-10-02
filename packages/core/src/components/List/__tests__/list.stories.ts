import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type ListProps = {
	ordered: boolean;
	classes?: string[];

}

const meta = {
	title: 'Text/List',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('ListComponent'),
	args: {
		ordered: false
	},
	argTypes: {
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		ordered: {
			description: "Whether to render an ordered list (ol) or unordered list (ul)",
			control: {
				type: "boolean"
			},
			table: {
				defaultValue: {
					summary: "false"
				},
				type: {
					summary: "boolean"
				}
			}
		}
	},
} satisfies Meta<ListProps>;

export default meta;
type Story = StoryObj<ListProps>;

export const Playground: Story = {
	tags: []
};
