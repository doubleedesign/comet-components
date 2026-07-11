import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { PullquoteProps } from '../../../../dist/types';

const meta = {
	title: 'Text/Pullquote',
	tags: ['autodocs'],
	...(await createStoryBase('Pullquote')),
} satisfies Meta<PullquoteProps>;

export default meta;
type Story = StoryObj<PullquoteProps>;

export const Playground: Story = {
	tags: ['!dev']
};
