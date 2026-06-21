import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type CalloutProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	iconPrefix: string;
	icon: string;
	classes?: string[];
}

const meta = {
	title: 'UI/Callout',
	tags: ['autodocs'],
	...(await createStoryBase('Callout')),
} satisfies Meta<CalloutProps>;

export default meta;
type Story = StoryObj<CalloutProps>;

export const Playground: Story = {
	tags: ['!dev']
};
