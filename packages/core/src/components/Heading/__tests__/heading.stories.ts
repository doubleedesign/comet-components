import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { HeadingProps } from '../../../../dist/types';

const meta = {
	title: 'Text/Heading',
	tags: ['autodocs'],
	...(await createStoryBase('Heading')),
} satisfies Meta<HeadingProps>;

export default meta;
type Story = StoryObj<HeadingProps>;

export const Playground: Story = {
	tags: ['!dev']
};
