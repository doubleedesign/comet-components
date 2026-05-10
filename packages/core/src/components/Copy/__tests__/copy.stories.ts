import type { Meta, StoryObj } from '@storybook/html-vite';
import {
	type ThemeColor,
	THEME_COLORS,
} from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

// TODO: Complete type, args, argTypes
type CopyProps = {
	colorTheme: ThemeColor;
	tagName: 'div';
}

const meta = {
	title: 'Text/Copy',
	tags: ['wordpress-block', 'autodocs'],
	...createStoryBase('Copy'),
} satisfies Meta<CopyProps>;

export default meta;
type Story = StoryObj<CopyProps>;

export const Playground: Story = {
	tags: []
};
