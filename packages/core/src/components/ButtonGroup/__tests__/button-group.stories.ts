import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Alignment, ALIGNMENT_OPTIONS, Orientation, ORIENTATION_OPTIONS, ThemeColor, THEME_COLORS} from '../../../../test/storybook-helpers.ts';

type ButtonGroupProps = {
	tagName: 'div';
	orientation: Orientation;
	hAlign: Alignment;
	vAlign: Alignment;
	classes?: string[];
	colorTheme?: ThemeColor;
}

const meta = {
	title: 'Text/ButtonGroup',
	tags: ['autodocs'],
	...(await createStoryBase('ButtonGroup')),
} satisfies Meta<ButtonGroupProps>;

export default meta;
type Story = StoryObj<ButtonGroupProps>;

export const Playground: Story = {
	tags: ['!dev']
};
