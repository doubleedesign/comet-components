import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type SeparatorProps = {
	tagName: 'hr';
	color: ThemeColor;
	classes?: string[];
}

const meta = {
	title: 'UI/Separator',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Separator')),
} satisfies Meta<SeparatorProps>;

export default meta;
type Story = StoryObj<SeparatorProps>;

export const Playground: Story = {
	tags: ['!dev']
};
