import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';

type DateRangeBlockProps = {
	tagName: 'time';
	colorTheme: ThemeColor;
	startDate: string;
	endDate: string;
	locale: string;
	showDay: boolean;
	showYear: boolean;
	classes?: string[];
}

const meta = {
	title: 'Text/DateRangeBlock',
	tags: ['autodocs'],
	...(await createStoryBase('DateRangeBlock')),
	args: {
		colorTheme: "primary",
		startDate: "2025-06-13",
		endDate: "2025-06-15",
		locale: "en_AU",
		showDay: false,
		showYear: true
	},
} satisfies Meta<DateRangeBlockProps>;

export default meta;
type Story = StoryObj<DateRangeBlockProps>;

export const Playground: Story = {
	tags: ['!dev']
};
