import React from "react";
import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from '../../../../test/story-base';
import { ContentImageAdvancedProps } from '../../../../dist/types';

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
