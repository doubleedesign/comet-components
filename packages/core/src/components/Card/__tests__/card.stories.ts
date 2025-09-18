import type { Meta, StoryObj } from '@storybook/html-vite';
import {
	type Orientation,
	type ThemeColor,
	THEME_COLORS,
	ORIENTATION_OPTIONS
} from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

// TODO: Complete type, args, argTypes
type CardProps = {
	colorTheme: ThemeColor;
	orientation: Orientation;
	tagName: 'div';
}

const meta = {
	title: 'UI/Card',
	tags: ['autodocs'],
	...createStoryBase('Card'),
} satisfies Meta<CardProps>;

export default meta;
type Story = StoryObj<CardProps>;

export const Playground: Story = {
	tags: []
};
