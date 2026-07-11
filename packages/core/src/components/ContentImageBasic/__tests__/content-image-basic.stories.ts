import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base";
import { ContentImageBasicProps } from '../../../../dist/types';

const meta = {
	title: 'Media/Content Image (Basic)',
	tags: ['wordpress-block', 'autodocs'],
	...(await createStoryBase('ContentImageBasic')),
} satisfies Meta<ContentImageBasicProps>;

export default meta;
type Story = StoryObj<ContentImageBasicProps>;

export const Playground: Story = {
	tags: ['!dev']
};
