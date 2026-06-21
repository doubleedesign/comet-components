import type { Meta, StoryObj } from '@storybook/html-vite';
import {
	type ThemeColor,
	THEME_COLORS,
	ORIENTATION_OPTIONS
} from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

// TODO: Complete type, args, argTypes
type PostNavProps = {
	colorTheme: ThemeColor;
}

const meta = {
	title: 'Layout/PostNav',
	tags: ['wordpress-theme', 'autodocs'],
	...(await createStoryBase('PostNav')),
} satisfies Meta<PostNavProps>;

export default meta;
type Story = StoryObj<PostNavProps>;

export const Playground: Story = {
	tags: ['!dev']
};
