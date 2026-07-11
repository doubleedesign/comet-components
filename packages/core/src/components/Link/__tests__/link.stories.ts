import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { LinkProps } from '../../../../dist/types';

const meta = {
	title: 'Media/Link',
	tags: ['autodocs'],
	...(await createStoryBase('Link')),
} satisfies Meta<LinkProps>;

export default meta;
type Story = StoryObj<LinkProps>;

export const Playground: Story = {
	tags: ['!dev']
};
