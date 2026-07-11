import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { BannerProps } from '../../../../dist/types';

const meta = {
	title: 'Layout/Banner',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Banner')),
} satisfies Meta<BannerProps>;

export default meta;
type Story = StoryObj<BannerProps>;

export const Playground: Story = {
	tags: ['!dev']
};
