import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type IconWithTextProps = {
	tagName: 'div';
	label: string;
	iconPrefix: string;
	icon: string;
	colorTheme: ThemeColor;
	classes?: string[];
}

const meta = {
	title: 'Text/IconWithText',
	tags: ['autodocs'],
	...(await createStoryBase('IconWithText')),
	args: {
		tagName: "div",
		label: "",
		iconPrefix: "fa-solid",
		icon: "fa-circle-check",
		colorTheme: "primary"
	},
} satisfies Meta<IconWithTextProps>;

export default meta;
type Story = StoryObj<IconWithTextProps>;

export const Playground: Story = {
	tags: ['!dev']
};
