import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type DetailsProps = {
	tagName: 'details';
	summary: string;
	classes?: string[];
}

const meta = {
	title: 'Text/Details',
	tags: ['autodocs'],
	...(await createStoryBase('Details')),
} satisfies Meta<DetailsProps>;

export default meta;
type Story = StoryObj<DetailsProps>;

export const Playground: Story = {
	tags: ['!dev']
};
