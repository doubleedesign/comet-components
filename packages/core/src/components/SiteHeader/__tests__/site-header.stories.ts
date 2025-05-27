import type { Meta, StoryObj } from '@storybook/html';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment } from "../../../../test/storybook-helpers.ts";

type SiteHeaderProps = {
	backgroundColor: '' | 'none' | 'primary' | 'secondary' | 'accent' | 'error' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'white';
	breakpoint: string;
	hAlign: Alignment;
	icon: string;
	iconPrefix: string;
	logoUrl: string;
	responsiveStyle: 'default' | 'overlay';
	size: 'default' | 'wide' | 'fullwidth' | 'narrow' | 'narrower' | 'small';
	submenuIcon: string;
	tagName: 'header';
	vAlign: Alignment;
	classes?: string[];
}

const meta = {
	title: 'Layout/SiteHeader',
	tags: ['vue', 'wordpress-theme', 'autodocs'],
	...createStoryBase('SiteHeader'),
	args: {
		backgroundColor: "",
		breakpoint: "768px",
		hAlign: "start",
		icon: "fa-bars",
		iconPrefix: "fa-solid",
		logoUrl: "",
		responsiveStyle: "default",
		size: "default",
		submenuIcon: "fa-chevron-down",
		tagName: "header",
		vAlign: "start"
	},
	argTypes: {
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
			options: [
				"none",
				"primary",
				"secondary",
				"accent",
				"error",
				"success",
				"warning",
				"info",
				"light",
				"dark",
				"white"
			]
		},
		breakpoint: {
			description: "Viewport breakpoint (in pixels or rem) at which to switch from the \"mobile\" style menu to \"desktop\" style menu. Use 0 to always use the \"mobile\" style or null to always use the \"desktop\" style",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "null"
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
					summary: "layout-block site-header"
				},
				type: {
					summary: "array<string>"
				}
			}
		},
		hAlign: {
			description: "",
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
			options: [
				"start",
				"end",
				"center",
				"justify",
				"match-parent"
			]
		},
		icon: {
			description: "Icon class name; for the responsive menu toggle button",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "fa-bars"
				},
				type: {
					summary: "string"
				}
			}
		},
		iconPrefix: {
			description: "Icon prefix class name",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "fa-solid"
				},
				type: {
					summary: "string"
				}
			}
		},
		logoUrl: {
			description: "The URL of the site logo image",
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
		responsiveStyle: {
			description: "The layout style of the responsive menu",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "default"
				},
				type: {
					summary: "string"
				}
			},
			options: [
				"default",
				"overlay"
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
			options: [
				"default",
				"wide",
				"fullwidth",
				"narrow",
				"narrower",
				"small"
			]
		},
		submenuIcon: {
			description: "Icon class name for the submenu toggle button in responsive mode",
			control: {
				type: "text"
			},
			table: {
				defaultValue: {
					summary: "fa-chevron-down"
				},
				type: {
					summary: "string"
				}
			}
		},
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "header"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"header"
			]
		},
		vAlign: {
			description: "",
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
			options: [
				"start",
				"end",
				"center",
				"justify",
				"match-parent"
			]
		}
	},
} satisfies Meta<SiteHeaderProps>;

export default meta;
type Story = StoryObj<SiteHeaderProps>;

export const Playground: Story = {
	tags: ['docsOnly']
};
