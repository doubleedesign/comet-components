import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type PullquoteProps = {
	tagName: 'blockquote';
	colorTheme: ThemeColor;
	citation: string;
	textAlign: '' | Alignment;
	textColor: ThemeColor
	classes?: string;
}

const meta = {
	title: 'Text/Pullquote',
	tags: ['autodocs'],
	...(await createStoryBase('Pullquote')),
} satisfies Meta<PullquoteProps>;

export default meta;
type Story = StoryObj<PullquoteProps>;

export const Playground: Story = {
	tags: ['!dev']
};
