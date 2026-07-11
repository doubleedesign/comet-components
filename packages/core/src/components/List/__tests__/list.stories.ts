import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { ListComponentProps as ListProps } from '../../../../dist/types';

const meta = {
	title: 'Text/List',
	tags: ['autodocs'],
	...(await createStoryBase('ListComponent')),
} satisfies Meta<ListProps>;

export default meta;
type Story = StoryObj<ListProps>;

export const Playground: Story = {
	tags: ['!dev']
};
