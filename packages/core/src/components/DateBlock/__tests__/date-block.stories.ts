import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

type DateBlockProps = {
	tagName: 'time';
	date: string;
	locale: string;
	showDay: boolean;
	showYear: boolean;
	classes?: string[];
}

const meta = {
	title: 'Text/DateBlock',
	tags: ['autodocs'],
	...(await createStoryBase('DateBlock')),
	args: {
		date: "2025-06-14",
		locale: "en_AU",
		showDay: false,
		showYear: true
	},
} satisfies Meta<DateBlockProps>;

export default meta;
type Story = StoryObj<DateBlockProps>;

export const Playground: Story = {
	tags: ['!dev']
};
