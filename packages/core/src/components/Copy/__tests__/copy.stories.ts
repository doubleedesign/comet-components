import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { CopyProps } from '../../../../dist/types';

const meta = {
	title: 'Text/Copy',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Copy')),
} satisfies Meta<CopyProps>;

export default meta;
type Story = StoryObj<CopyProps>;

export const Playground: Story = {
	tags: ['!dev']
};
