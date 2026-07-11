import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../../test/story-base.ts";
import { FileProps } from '../../../../../dist/types';

const meta = {
	title: 'Media/File',
	tags: ['autodocs'],
	...(await createStoryBase('File')),
	args: {
		tagName: "div",
		colorTheme: null,
		iconPrefix: "fa-solid",
		icon: "fa-file",
	},
} satisfies Meta<FileProps>;

export default meta;
type Story = StoryObj<FileProps>;

export const Playground: Story = {
	tags: ['!dev']
};
