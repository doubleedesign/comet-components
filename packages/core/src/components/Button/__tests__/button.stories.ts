import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ButtonProps } from '../../../../dist/types';

const meta = {
	title: 'Text/Button',
	tags: ['autodocs'],
	...(await createStoryBase('Button')),
} satisfies Meta<ButtonProps>;

export default meta;
type Story = StoryObj<ButtonProps>;

export const Playground: Story = {
	tags: ['!dev']
};
