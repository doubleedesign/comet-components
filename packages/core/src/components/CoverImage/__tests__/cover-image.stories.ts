import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base";
import { CoverImageProps } from '../../../../dist/types';

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
