import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type CallToActionProps = {
	tagName: 'div' | 'section' | 'aside';
	backgroundColor: ThemeColor
	classes?: string[];
}

const meta = {
	title: 'UI/CallToAction',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('CallToAction')),
} satisfies Meta<CallToActionProps>;

export default meta;
type Story = StoryObj<CallToActionProps>;

export const Playground: Story = {
	tags: ['!dev']
};
