import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ContainerSize, CONTAINER_SIZES, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type PageHeaderProps = {
	tagName: 'header' | 'div' | 'section';
	size: ContainerSize;
	backgroundColor: '' | ThemeColor;
	classes?: string[];
}

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
