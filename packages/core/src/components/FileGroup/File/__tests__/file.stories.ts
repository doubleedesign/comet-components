import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../../test/story-base.ts";
import { THEME_COLORS } from "../../../../../test/storybook-helpers.ts";

type FileProps = {
	tagName: 'div';
	size: string;
	colorTheme: 'primary' | 'secondary' | 'accent' | 'error' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'white' | null;
	iconPrefix: string;
	icon: string;
	url: string;
	description: string;
	mimeType: string;
	title: string;
	uploadDate: string;
	classes?: string[];

}

const meta = {
	title: 'Media/File',
	tags: ['autodocs'],
	...createStoryBase('File'),
	args: {
		tagName: "div",
		size: "",
		colorTheme: null,
		iconPrefix: "fa-solid",
		icon: "fa-file",
		url: "",
		description: "",
		mimeType: "",
		title: "",
		uploadDate: ""
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "div"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"div"
			]
		},
		size: {
			description: "",
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
		colorTheme: {
			description: "Colour keyword for the fill or outline colour",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "null"
				},
				type: {
					summary: "ThemeColor"
				}
			},
			options: THEME_COLORS
		},
		iconPrefix: {
			description: "Icon prefix class name",
			control: false,
			table: {
				defaultValue: {
					summary: "fa-solid"
				},
				type: {
					summary: "string"
				}
			}
		},
		icon: {
			description: "Icon class name; default values set for file types including PDF, plain text, calendar, Word, Excel, ZIP, GZIP, TAR, and 7z",
			control: false,
			table: {
				defaultValue: {
					summary: "fa-file"
				},
				type: {
					summary: "string"
				}
			}
		},
		url: {
			description: "",
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
					summary: "file"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		description: {
			description: "",
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
		mimeType: {
			description: "MIME type of the file",
			control: false,
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "string|mixed"
				}
			}
		},
		title: {
			description: "",
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
		uploadDate: {
			description: "",
			control: false,
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
} satisfies Meta<FileProps>;

export default meta;
type Story = StoryObj<FileProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
