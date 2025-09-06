import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base";

type ContentImageAdvancedProps = {
	tagName: 'figure';
	alt: string;
	caption: string;
	title: string;
	src?: string;
	href?: string;
	align?: 'left' | 'center' | 'right' | 'full';
	aspectRatio?: 'none' | '4:3' | '3:4' | '1:1' | '16:9' | '9:16' | '3:2' | '2:3';
	focalPoint?: { x: number; y: number; };
	offset?: { x: number; y: number; };
}

const meta = {
	title: 'Media/Content Image (Advanced)',
	tags: ['autodocs'],
	...createStoryBase('ContentImageAdvanced'),
	args: {
		tagName: "figure",
		alt: "Bicycle in Amsterdam",
		caption: "Bicycle in Amsterdam",
		title: "Bicycle in Amsterdam",
		focalPoint: { x: 50, y: 50 },
		offset: { x: 0, y: 0 },
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
		href: {
			description: "URL to link to",
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
		caption: {
			description: "Optional image caption (to appear below the image)",
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
		align: {
			description: "Image alignment",
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
				"left",
				"center",
				"right",
				"full"
			]
		},
		aspectRatio: {
			description: "Crop image to the given aspect ratio",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "AspectRatio"
				}
			},
			options: [
				"none",
				"4:3",
				"3:4",
				"1:1",
				"16:9",
				"9:16",
				"3:2",
				"2:3"
			]
		},
		focalPoint: {
			description: "Focal point for the image (x and y coordinates from 0 to 100)",
			control: {
				type: "object"
			},
			table: {
				defaultValue: {
					summary: "{ x: 50, y: 50 }"
				},
				type: {
					summary: "{ x: number; y: number; }"
				}
			}
		},
		offset: {
			description: "Offset the image position (x and y coordinates in percentages) to customise the aspect ratio cropping",
			control: {
				type: "object"
			},
			table: {
				defaultValue: {
					summary: "{ x: 0, y: 0 }"
				},
				type: {
					summary: "{ x: number; y: number; }"
				}
			}
		},
	},
} satisfies Meta<ContentImageAdvancedProps>;

export default meta;
type Story = StoryObj<ContentImageAdvancedProps>;

export const Playground: Story = {
	tags: []
};
