import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base";

type CoverImageProps = {
	tagName: 'div';
	alt: string;
	caption: string;
	title: string;
	isParallax: boolean;
	src?: string;
	href?: string;
}

const meta = {
	title: 'Media/Cover Image',
	tags: ['autodocs'],
	...createStoryBase('CoverImage'),
	args: {
		tagName: "div",
		alt: "Bicycle in Amsterdam",
		caption: "Bicycle in Amsterdam",
		title: "Bicycle in Amsterdam",
		isParallax: false,
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "figure"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"figure"
			]
		},
		src: {
			description: "Image source URL",
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
		title: {
			description: "Optional image title (appears on hover)",
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
		},
		alt: {
			description: "Alternative text",
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
		},
		isParallax: {
			description: "In relevant contexts, whether the image should be used to achieve a parallax effect (requires CSS to actually execute)",
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
	},
} satisfies Meta<CoverImageProps>;

export default meta;
type Story = StoryObj<CoverImageProps>;

export const Playground: Story = {
	tags: []
};
