import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type BreadcrumbsProps = {
	tagName: 'nav';
	classes?: string[];
}

const meta = {
	title: 'Navigation/Breadcrumbs',
	tags: ['wordpress-theme', 'autodocs'],
	...(await createStoryBase('Breadcrumbs')),
} satisfies Meta<BreadcrumbsProps>;

export default meta;
type Story = StoryObj<BreadcrumbsProps>;

export const Playground: Story = {
	tags: ['!dev']
};
