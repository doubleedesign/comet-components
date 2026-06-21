import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type GroupProps = {
	backgroundColor: '' | 'none' | ThemeColor;
	tagName: 'div' | 'section' | 'article' | 'aside';
	classes?: string | string[];

}

const meta = {
	title: 'Layout/Group',
	tags: ['autodocs'],
	...(await createStoryBase('Group')),
} satisfies Meta<GroupProps>;

export default meta;
type Story = StoryObj<GroupProps>;

export const Playground: Story = {
	tags: ['!dev']
};
