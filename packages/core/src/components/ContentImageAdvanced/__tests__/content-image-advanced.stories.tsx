import React from "react";
import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from '../../../../test/story-base';

type ContentImageAdvancedProps = {
	tagName: 'figure';
	alt: string;
	caption: string;
	title: string;
	src?: string;
	align?: 'left' | 'center' | 'right' | 'full';
	aspectRatio?: 'none' | '4:3' | '3:4' | '1:1' | '16:9' | '9:16' | '3:2' | '2:3' | '21:9' | '2.35:1';
	focalPointX?: number;
	focalPointY?: number;
	focalPoint?: { x: number; y: number; };
	offsetX?: number;
	offsetY?: number;
	offset?: { x: number; y: number; };
}

const meta = {
	title: 'Media/Content Image (Advanced)',
	tags: ['autodocs'],
	...(await createStoryBase('ContentImageAdvanced')),
} satisfies Meta<ContentImageAdvancedProps>;

export default meta;
type Story = StoryObj<ContentImageAdvancedProps>;


const common = {
	parameters: {
		controls: {
			exclude: ['focalPoint', 'offset', 'src']
		}
	},
	args: {
		focalPointX: 50,
		focalPointY: 50,
		offsetX: 0,
		offsetY: 0,
	},
}

export const DocsStory: Story = {
	tags: ['docsOnly'],
};

export const PlaygroundPortrait: Story = {
	name: "Playground (Portrait image)",
	tags: [],
	...common,
	args: {
		...common.args,
		src: "https://cometcomponents.io/tests/assets/example-image-1.jpg",
		aspectRatio: '2:3'
	},
};

export const PlaygroundLandscape: Story = {
	name: "Playground (Landscape image)",
	tags: [],
	...common,
	args: {
		...common.args,
		src: "https://cometcomponents.io/tests/assets/example-image-2.jpg",
		aspectRatio: '16:9'
	},
}
