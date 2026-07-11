import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { SiteFooterProps } from '../../../../dist/types';

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
