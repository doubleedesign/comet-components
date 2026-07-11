import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { IconLinksProps } from '../../../../dist/types';

const meta = {
	title: 'UI/IconLinks',
	tags: ['javascript', 'wordpress-theme', 'autodocs'],
	...(await createStoryBase('IconLinks')),
	args: {
		tagName: "div",
		orientation: "horizontal",
		hAlign: "center",
		vAlign: "start",
		iconPrefix: "fa-brands"
	},
} satisfies Meta<IconLinksProps>;

export default meta;
type Story = StoryObj<IconLinksProps>;

export const Playground: Story = {
	tags: ['!dev']
};
