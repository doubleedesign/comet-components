import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { GalleryProps } from '../../../../dist/types';

const meta = {
	title: 'Media/Gallery',
	tags: ['wordpress-block', 'javascript', 'autodocs'],
	...(await createStoryBase('Gallery')),
	args: {
		caption: "",
		maxPerRow: 3,
		imageCrop: true,
	},
} satisfies Meta<GalleryProps>;

export default meta;
type Story = StoryObj<GalleryProps>;

export const Playground: Story = {
	tags: ['!dev']
};
