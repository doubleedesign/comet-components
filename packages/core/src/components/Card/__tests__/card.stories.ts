import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { CardProps } from '../../../../dist/types';

const meta = {
	title: 'UI/Card',
	tags: ['autodocs'],
	...(await createStoryBase('Card')),
} satisfies Meta<CardProps>;

export default meta;
type Story = StoryObj<CardProps>;

export const Playground: Story = {
	tags: ['!dev']
};
