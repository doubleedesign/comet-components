import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { SeparatorProps } from '../../../../dist/types';

const meta = {
	title: 'UI/Separator',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Separator')),
} satisfies Meta<SeparatorProps>;

export default meta;
type Story = StoryObj<SeparatorProps>;

export const Playground: Story = {
	tags: ['!dev']
};
