import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ContainerSize, CONTAINER_SIZES, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type ContainerProps = {
	withWrapper: boolean;
	tagName: 'section' | 'main' | 'div';
	size: ContainerSize;
	backgroundColor: '' | 'none' | ThemeColor;
	gradient: string;
	classes?: string[];
}

const meta = {
	title: 'Structure/Container',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Container'),
	args: {
		withWrapper: true,
		tagName: "section",
		size: "default",
		backgroundColor: "",
		gradient: ""
	},
	argTypes: {
		withWrapper: {
			description: "Whether to wrap the container element so that the background is full-width",
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
		tagName: {
			description: "The HTML tag to use for this component",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "section"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"section",
				"main",
				"div"
			]
		},
		size: {
			description: "Keyword specifying the relative width of the container for the inner content",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "default"
				},
				type: {
					summary: "ContainerSize"
				}
			},
			options: CONTAINER_SIZES
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
			options: ["none", ...THEME_COLORS]
		},
		gradient: {
			description: "Name of a gradient to use for the background (requires accompanying CSS to be defined)",
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
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "layout-block container"
				},
				type: {
					summary: "array<string>"
				}
			}
		}
	},
} satisfies Meta<ContainerProps>;

export default meta;
type Story = StoryObj<ContainerProps>;

export const Playground: Story = {
	tags: []
};
