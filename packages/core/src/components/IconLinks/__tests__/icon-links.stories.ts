import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, Orientation, ORIENTATION_OPTIONS } from '../../../../test/storybook-helpers.ts';

type IconLinksProps = {
	tagName: 'div';
	orientation: Orientation;
	hAlign: Alignment;
	vAlign: Alignment;
	iconPrefix: string;
	classes?: string[];
}

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
