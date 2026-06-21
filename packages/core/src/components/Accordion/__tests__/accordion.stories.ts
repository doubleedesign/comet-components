import type { Meta, StoryObj } from '@storybook/html-vite';
import { type Orientation, type ThemeColor, THEME_COLORS, ORIENTATION_OPTIONS } from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

type AccordionProps = {
	colorTheme: ThemeColor;
	icon: string;
	iconPrefix: string;
	orientation: Orientation;
	tagName: 'div';
	classes?: string[];
}

const meta = {
	title: 'Layout/Accordion',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...(await createStoryBase('Accordion')),
} satisfies Meta<AccordionProps>;

export default meta;
type Story = StoryObj<AccordionProps>;

export const Playground: Story = {
	tags: ['!dev']
};
