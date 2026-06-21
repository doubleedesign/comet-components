import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type MenuProps = {
	tagName: 'nav';
	classes?: string[];
}

const meta = {
	title: 'Navigation/Menu',
	tags: ['wordpress-theme', 'autodocs'],
	...(await createStoryBase('Menu')),
} satisfies Meta<MenuProps>;

export default meta;
type Story = StoryObj<MenuProps>;

export const Playground: Story = {
	tags: ['!dev']
};
