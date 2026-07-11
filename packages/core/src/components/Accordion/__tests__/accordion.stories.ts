import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { AccordionProps } from '../../../../dist/types';

const meta = {
	title: 'Layout/Accordion',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...(await createStoryBase('Accordion')),
} satisfies Meta<AccordionProps>;

export default meta;
type Story = StoryObj<AccordionProps>;

export const Playground: Story = {
	tags: ['!dev']
};
