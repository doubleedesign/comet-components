import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type CallToActionProps = {
	tagName: 'div' | 'section' | 'aside';
	backgroundColor: ThemeColor
	classes?: string[];
}

const meta = {
	title: 'UI/CallToAction',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('CallToAction'),
	args: {
		tagName: "div",
		backgroundColor: "",

	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "div"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"div",
				"section",
				"aside"
			]
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
			control: false,
			table: {
				defaultValue: {
					summary: "call-to-action"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<CallToActionProps>;

export default meta;
type Story = StoryObj<CallToActionProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
