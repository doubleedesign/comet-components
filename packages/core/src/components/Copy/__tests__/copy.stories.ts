import type { Meta, StoryObj } from '@storybook/html-vite';
import { type ThemeColor } from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

type CopyProps = {
	colorTheme: ThemeColor;
	tagName: 'div';
}

const meta = {
	title: 'Text/Copy',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Copy')),
} satisfies Meta<CopyProps>;

export default meta;
type Story = StoryObj<CopyProps>;

export const Playground: Story = {
	tags: ['!dev']
};
