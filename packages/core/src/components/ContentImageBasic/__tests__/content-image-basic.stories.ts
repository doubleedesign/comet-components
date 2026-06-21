import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base";

type ContentImageBasicProps = {
	tagName: 'figure';
	alt: string;
	caption: string;
	title: string;
	src?: string;
	href?: string;
	align?: 'left' | 'center' | 'right' | 'full';
	width: string;
	height: string;
	classes?: '' | 'is-style-rounded';
	aspectRatio?: 'none' | '4:3' | '3:4' | '1:1' | '16:9' | '9:16' | '3:2' | '2:3';
	scale: 'contain' | 'cover';
}

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
