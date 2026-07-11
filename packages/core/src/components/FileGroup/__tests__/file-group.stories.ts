import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { FileGroupProps } from '../../../../dist/types';

const meta = {
	title: 'Media/FileGroup',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('FileGroup')),
} satisfies Meta<FileGroupProps>;

export default meta;
type Story = StoryObj<FileGroupProps>;

export const Playground: Story = {
	tags: ['!dev']
};
