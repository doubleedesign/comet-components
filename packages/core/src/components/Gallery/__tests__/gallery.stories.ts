import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type GalleryProps = {
	tagName: 'figure' | 'div';
	caption: string;
	columns: 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8;
	classes?: string[];
}

const meta = {
	title: 'Media/Gallery',
	tags: ['wordpress-block', 'javascript', 'autodocs'],
	...(await createStoryBase('Gallery')),
	args: {
		tagName: "figure",
		caption: "",
		columns: 3
	},
} satisfies Meta<GalleryProps>;

export default meta;
type Story = StoryObj<GalleryProps>;

export const Playground: Story = {
	tags: ['!dev']
};
