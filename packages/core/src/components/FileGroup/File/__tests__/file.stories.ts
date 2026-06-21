import type { Meta, StoryObj } from '@storybook/html-vite';
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
	...(await createStoryBase('File')),
	args: {
		tagName: "div",
		colorTheme: null,
		iconPrefix: "fa-solid",
		icon: "fa-file",
	},
} satisfies Meta<FileProps>;

export default meta;
type Story = StoryObj<FileProps>;

export const Playground: Story = {
	tags: ['!dev']
};
