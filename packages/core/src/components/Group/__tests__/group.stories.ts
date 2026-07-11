import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { GroupProps } from '../../../../dist/types';

const meta = {
	title: 'Layout/Group',
	tags: ['autodocs'],
	...(await createStoryBase('Group')),
} satisfies Meta<GroupProps>;

export default meta;
type Story = StoryObj<GroupProps>;

export const Playground: Story = {
	tags: ['!dev']
};
