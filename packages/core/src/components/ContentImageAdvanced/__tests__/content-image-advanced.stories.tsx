import React from "react";
import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from '../../../../test/story-base';

type ContentImageAdvancedProps = {
	tagName: 'figure';
	alt: string;
	caption: string;
	title: string;
	src?: string;
	align?: 'left' | 'center' | 'right' | 'full';
	aspectRatio?: 'none' | '4:3' | '3:4' | '1:1' | '16:9' | '9:16' | '3:2' | '2:3' | '21:9' | '2.35:1';
	focalPointX?: number;
	focalPointY?: number;
	focalPoint?: { x: number; y: number; };
	offsetX?: number;
	offsetY?: number;
	offset?: { x: number; y: number; };
}

const meta = {
	title: 'Media/Content Image (Advanced)',
	tags: ['autodocs'],
	...createStoryBase('ContentImageAdvanced'),
	args: {
		aspectRatio: "1:1",
		focalPoint: { x: 50, y: 50 },
		offset: { x: 0, y: 0 },
		src: "https://cometcomponents.io/tests/assets/example-image-1.jpg",
		align: "center",
		tagName: 'figure',
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: { type: "select" },
			options: ["figure", "div"],
			table: {
				defaultValue: { summary: "figure" },
				type: { summary: "Tag" }
			},
		},
		src: {
			description: "Image source URL",
			control: 'select',
			options: [
				'https://cometcomponents.io/tests/assets/example-image-1.jpg',
				'https://cometcomponents.io/tests/assets/example-image-2.jpg',
			],
			table: {
				defaultValue: { summary: "" },
				type: { summary: "string" }
			}
		},
		align: {
			description: "Image alignment",
			control: { type: "select" },
			table: {
				defaultValue: { summary: "" },
				type: { summary: "string" }
			},
			options: ["left", "center", "right", "full"]
		},
		aspectRatio: {
			description: "Crop image to the given aspect ratio",
			control: {
				type: "select",
				labels: {
					"4:3": "4:3 (Standard)",
					"3:4": "3:4 (Portrait)",
					"1:1": "1:1 (Square)",
					"16:9": "16:9 (Widescreen)",
					"9:16": "9:16 (Tall)",
					"3:2": "3:2 (Classic)",
					"2:3": "2:3 (Classic portrait)",
					"21:9": "21:9 (Cinematic)",
					"2.35:1": "2.35:1 (Cinemascope)"
				}
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
				"2:3",
				"21:9",
				"2.35:1"
			],
		},
		focalPoint: {
			description: "Focal point for the image (x and y coordinates from 0 to 100)",
			control: false,
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
			control: false,
			table: {
				defaultValue: {
					summary: "{ x: 0, y: 0 }"
				},
				type: {
					summary: "{ x: number; y: number; }"
				}
			}
		},
		alt: {
			description: "Alternative text",
			control: { type: "text" },
			table: {
				defaultValue: { summary: "" },
				type: { summary: "string" }
			}
		},
		caption: {
			description: "Optional image caption (to appear below the image)",
			control: {
				type: "text"
			},
			table: {
				defaultValue: { summary: "" },
				type: { summary: "string" }
			}
		},
		title: {
			description: "Optional image title (appears on hover)",
			control: { type: "text" },
			table: {
				defaultValue: { summary: "" },
				type: { summary: "string" }
			}
		},
	},
} satisfies Meta<ContentImageAdvancedProps>;

export default meta;
type Story = StoryObj<ContentImageAdvancedProps>;


const common = {
	parameters: {
		controls: {
			exclude: ['focalPoint', 'offset', 'src']
		}
	},
	args: {
		focalPointX: 50,
		focalPointY: 50,
		offsetX: 0,
		offsetY: 0,
	},
	argTypes: {
		focalPointX: {
			control: {
				type: 'number',
				min: 0,
				max: 100,
				step: 10
			}
		},
		focalPointY: {
			control: {
				type: 'number',
				min: 0,
				max: 100,
				step: 10
			}
		},
		offsetX: {
			control: {
				type: 'number',
				min: -200,
				max: 100,
				step: 10
			}
		},
		offsetY: {
			control: {
				type: 'number',
				min: -200,
				max: 100,
				step: 10,
				readonly: true
			}
		}
	}
}

export const DocsStory: Story = {
	tags: ['docsOnly'],
};

export const PlaygroundPortrait: Story = {
	name: "Playground (Portrait image)",
	tags: [],
	...common,
	args: {
		...common.args,
		src: "https://cometcomponents.io/tests/assets/example-image-1.jpg",
		aspectRatio: '2:3'
	},
};

export const PlaygroundLandscape: Story = {
	name: "Playground (Landscape image)",
	tags: [],
	...common,
	args: {
		...common.args,
		src: "https://cometcomponents.io/tests/assets/example-image-2.jpg",
		aspectRatio: '16:9'
	},
}
