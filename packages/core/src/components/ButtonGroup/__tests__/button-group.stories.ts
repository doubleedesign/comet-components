import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ButtonGroupProps } from '../../../../dist/types';

const meta = {
	title: 'Text/ButtonGroup',
	tags: ['autodocs'],
	...(await createStoryBase('ButtonGroup')),
} satisfies Meta<ButtonGroupProps>;

export default meta;
type Story = StoryObj<ButtonGroupProps>;

export const Playground: Story = {
	tags: ['!dev']
};
