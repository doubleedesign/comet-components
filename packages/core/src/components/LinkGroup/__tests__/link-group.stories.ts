import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type LinkGroupProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	classes?: string[];

}

const meta = {
	title: 'Media/LinkGroup',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('LinkGroup')),
} satisfies Meta<LinkGroupProps>;

export default meta;
type Story = StoryObj<LinkGroupProps>;

export const Playground: Story = {
	tags: ['!dev']
};
