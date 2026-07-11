import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { PostNavProps } from '../../../../dist/types';

const meta = {
	title: 'Layout/PostNav',
	tags: ['wordpress-theme', 'autodocs'],
	...(await createStoryBase('PostNav')),
} satisfies Meta<PostNavProps>;

export default meta;
type Story = StoryObj<PostNavProps>;

export const Playground: Story = {
	tags: ['!dev']
};
