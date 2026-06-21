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
	...(await createStoryBase('CoverImage')),
} satisfies Meta<CoverImageProps>;

export default meta;
type Story = StoryObj<CoverImageProps>;

export const Playground: Story = {
	tags: ['!dev']
};
