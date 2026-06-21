import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type ButtonProps = {
	tagName: 'a' | 'button';
	colorTheme: ThemeColor;
	isOutline: boolean;
	href?: string;
	classes?: string[];
}

const meta = {
	title: 'Text/Button',
	tags: ['autodocs'],
	...(await createStoryBase('Button')),
} satisfies Meta<ButtonProps>;

export default meta;
type Story = StoryObj<ButtonProps>;

export const Playground: Story = {
	tags: ['!dev']
};
