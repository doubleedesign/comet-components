import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { IconWithTextProps } from '../../../../dist/types';

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
