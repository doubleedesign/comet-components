import type { Meta, StoryObj } from '@storybook/html-vite';
import {
	type ThemeColor,
	THEME_COLORS,
} from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

// TODO: Complete type, args, argTypes
type CopyBlockProps = {
	colorTheme: ThemeColor;
	tagName: 'div';
}

const meta = {
	title: 'Text/CopyBlock',
	tags: ['autodocs'],
	...createStoryBase('CopyBlock'),
} satisfies Meta<CopyBlockProps>;

export default meta;
type Story = StoryObj<CopyBlockProps>;

export const Playground: Story = {
	tags: []
};
