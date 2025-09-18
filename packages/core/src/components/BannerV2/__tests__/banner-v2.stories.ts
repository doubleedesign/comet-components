import type { Meta, StoryObj } from '@storybook/html-vite';
import {
	type ThemeColor,
	THEME_COLORS,
	ORIENTATION_OPTIONS
} from "../../../../test/storybook-helpers.ts";
import { createStoryBase } from "../../../../test/story-base.ts";

// TODO: Complete type, args, argTypes
type BannerV2Props = {
	colorTheme: ThemeColor;
}

const meta = {
	title: 'Layout/BannerV2',
	tags: ['autodocs'],
	...createStoryBase('BannerV2'),
} satisfies Meta<BannerV2Props>;

export default meta;
type Story = StoryObj<BannerV2Props>;

export const Playground: Story = {
	tags: []
};
