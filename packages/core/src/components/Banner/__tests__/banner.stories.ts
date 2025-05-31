import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, ContainerSize, CONTAINER_SIZES, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type BannerProps = {
	tagName: 'section';
	hAlign: Alignment;
	vAlign: Alignment;
	backgroundColor: 'none' | ThemeColor;
	containerSize: ContainerSize;
	contentMaxWidth: number;
	focalPoint: [number, number];
	isParallax: boolean;
	maxHeight: number;
	minHeight: number;
	overlayColor: 'none' | ThemeColor;
	overlayOpacity: number;
	imageAlt?: string;
	imageUrl?: string;
	classes?: string[];
}

const meta = {
	title: 'Layout/Banner',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Banner'),
	args: {
		tagName: "section",
		hAlign: "start",
		vAlign: "center",
		backgroundColor: "dark",
		containerSize: "default",
		contentMaxWidth: 50,
		focalPoint: [50, 50],
		isParallax: false,
		maxHeight: 40,
		minHeight: 600,
		overlayColor: "dark",
		overlayOpacity: 30
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "section"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"section"
			]
		},
		hAlign: {
			description: "Horizontal alignment, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "start"
				},
				type: {
					summary: "Alignment"
				}
			},
			options: ALIGNMENT_OPTIONS
		},
		vAlign: {
			description: "Vertical alignment, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "start"
				},
				type: {
					summary: "Alignment"
				}
			},
			options: ALIGNMENT_OPTIONS
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
		containerSize: {
			description: "The size of the container for the content",
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
		contentMaxWidth: {
			description: "The maximum width of the content area as a percentage of the container (may be overridden to full width for small viewports/containers)",
			control: {
				type: "number"
			},
			table: {
				defaultValue: {
					summary: "50"
				},
				type: {
					summary: "int"
				}
			}
		},
		focalPoint: {
			description: "The X and Y coordinates of the focal point of the image",
			control: {
				type: "object",
				options: {
					number: {
						min: 0,
						max: 100
					}
				}
			},
			table: {
				defaultValue: {
					summary: "[50, 50]"
				},
				type: {
					summary: "array<int,int>"
				}
			}
		},
		imageAlt: {
			description: "The alt text for the image",
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
		imageUrl: {
			description: "The URL of the image to display in the banner",
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
		isParallax: {
			description: "Whether the banner should have a fixed background (also known as a parallax effect)",
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
		maxHeight: {
			description: "The maximum height of the banner (in vh)",
			control: {
				type: "number"
			},
			table: {
				defaultValue: {
					summary: "100"
				},
				type: {
					summary: "int"
				}
			}
		},
		minHeight: {
			description: "The minimum height of the banner (in px)",
			control: {
				type: "number"
			},
			table: {
				defaultValue: {
					summary: "600"
				},
				type: {
					summary: "int"
				}
			}
		},
		overlayColor: {
			description: "The color of the overlay on top of the image",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "dark"
				},
				type: {
					summary: "ThemeColor"
				}
			},
			options: ["none", ...THEME_COLORS]
		},
		overlayOpacity: {
			description: "The opacity of the overlay on top of the image (set to 0 to disable the overlay)",
			control: {
				type: "number"
			},
			table: {
				defaultValue: {
					summary: "0"
				},
				type: {
					summary: "int"
				}
			}
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "layout-block banner"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
} satisfies Meta<BannerProps>;

export default meta;
type Story = StoryObj<BannerProps>;

export const Playground: Story = {
	tags: []
};
