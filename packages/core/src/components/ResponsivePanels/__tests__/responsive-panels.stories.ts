import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Orientation, ORIENTATION_OPTIONS, THEME_COLORS, ThemeColor } from "../../../../test/storybook-helpers.ts";

type ResponsivePanelsProps = {
	breakpoint: string;
	colorTheme: ThemeColor;
	icon: string;
	iconPrefix: string;
	orientation: Orientation;
	tagName: 'div';
	classes?: string[];
}

const meta = {
	title: 'Layout/ResponsivePanels',
	tags: ['vue', 'autodocs'],
	...(await createStoryBase('ResponsivePanels')),
} satisfies Meta<ResponsivePanelsProps>;

export default meta;
type Story = StoryObj<ResponsivePanelsProps>;

export const Playground: Story = {
	tags: ['!dev']
};
