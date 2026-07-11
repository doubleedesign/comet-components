import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { PageHeaderProps } from '../../../../dist/types';

const meta = {
	title: 'UI/PageHeader',
	tags: ['wordpress-theme', 'autodocs'],
	...(await createStoryBase('PageHeader')),
} satisfies Meta<PageHeaderProps>;

export default meta;
type Story = StoryObj<PageHeaderProps>;

export const Playground: Story = {
	tags: ['!dev']
};
