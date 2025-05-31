import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type ImageProps = {
	tagName: 'figure';
	alt: string;
	caption: string;
	title: string;
	width: string;
	height: string;
	isParallax: boolean;
	scale: 'contain' | 'cover';
	classes?: '' | 'is-style-rounded';
	src?: string;
	href?: string;
	align?: 'left' | 'center' | 'right' | 'full';
	aspectRatio?: 'none' | '4:3' | '3:4' | '1:1' | '16:9' | '9:16' | '3:2' | '2:3';

}

const meta = {
	title: 'Media/Image',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Image'),
	args: {
		tagName: "figure",
		alt: "Bicycle in Amsterdam",
		caption: "Bicycle in Amsterdam",
		title: "Bicycle in Amsterdam",
		width: "400px",
		height: "",
		isParallax: false,
		scale: "contain"
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
		classes: {
			description: "CSS classes",
			control: {
				type: "select"
			},
			options: [
				"",
				"is-style-rounded"
			],
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "string"
				}
			}
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
		width: {
			description: "Set a fixed width for the image",
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
		height: {
			description: "Set a fixed height for the image",
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
		scale: {
			description: "How to handle how the image fits the available space",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "contain"
				},
				type: {
					summary: "string"
				}
			},
			options: [
				"contain",
				"cover"
			]
		},

	},
} satisfies Meta<ImageProps>;

export default meta;
type Story = StoryObj<ImageProps>;

export const Playground: Story = {
	tags: []
};
