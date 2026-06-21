import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type SiteFooterProps = {
	backgroundColor: '' | 'none' | 'primary' | 'secondary' | 'accent' | 'error' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'white';
	size: 'default' | 'wide' | 'fullwidth' | 'narrow' | 'narrower' | 'small';
	tagName: 'footer';
	classes?: string[];
}

const meta = {
	title: 'Layout/SiteFooter',
	tags: ['wordpress-theme', 'autodocs'],
	...(await createStoryBase('SiteFooter')),
} satisfies Meta<SiteFooterProps>;

export default meta;
type Story = StoryObj<SiteFooterProps>;

export const Playground: Story = {
	tags: ['!dev']
};
