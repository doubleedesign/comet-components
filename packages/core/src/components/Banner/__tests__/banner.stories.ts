import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, ContainerSize, CONTAINER_SIZES, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type BannerProps = {
	tagName: 'section';
	hAlign: Alignment;
	vAlign: Alignment;
	backgroundColor: 'none' | ThemeColor;
	containerSize: ContainerSize;
	contentMaxWidth: number;
	isParallax: boolean;
	maxHeight: number;
	minHeight: number;
	overlayColor: 'none' | ThemeColor;
	overlayOpacity: number;
	imageAlt?: string;
	imageUrl?: string;
	classes?: string[];
}

const meta = {
	title: 'Layout/Banner',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('Banner')),
} satisfies Meta<BannerProps>;

export default meta;
type Story = StoryObj<BannerProps>;

export const Playground: Story = {
	tags: ['!dev']
};
