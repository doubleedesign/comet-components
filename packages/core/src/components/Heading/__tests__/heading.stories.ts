import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type HeadingProps = {
	level: 1 | 2 | 3 | 4 | 5 | 6;
	textAlign: '' | Alignment;
	textColor: ThemeColor
	classes: '' | 'is-style-accent' | 'is-style-small';

}

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
