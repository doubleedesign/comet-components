import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";

const storyBase = await createStoryBase('SiteHeader');

const meta = {
	title: 'Layout/SiteHeader',
	tags: ['vue', 'wordpress-theme', 'autodocs'],
	...storyBase,
	args: {
		...storyBase.args,
		breakpoint: "768px",
		overlayBackgroundColor: 'primary',
		size: 'contained',
	},
	parameters: {
		...(storyBase.parameters || {}),
		controls: {
			exclude: ['context']
		}
	}
} satisfies Meta<any>;

export default meta;
type Story = StoryObj<any>;

export const Playground: Story = {
	tags: ['!dev']
};

export const BasicStyle: Story = {
	name: 'Responsive style: Basic',
	args: {
		responsiveStyle: 'basic',
		breakpoint: null,
	},
	argTypes: {
		responsiveStyle: {
			// @ts-expect-error TS2339: Property responsiveStyle does not exist on type {}
			...meta?.argTypes?.responsiveStyle,
			control: { disable: true }
		}
	}
}

export const OverlayStyle: Story = {
	name: 'Below breakpoint mode: Overlay',
	args: {
		responsiveStyle: 'overlay',
		breakpoint: 0
	},
	argTypes: {
		responsiveStyle: {
			// @ts-expect-error TS2339: Property responsiveStyle does not exist on type {}
			...meta?.argTypes?.responsiveStyle,
			control: { disable: true }
		}
	}
}

export const OffCanvasStyle: Story = {
	name: 'Below breakpoint mode: Off-canvas',
	args: {
		responsiveStyle: 'off-canvas',
		breakpoint: 0
	},
	argTypes: {
		responsiveStyle: {
			// @ts-expect-error TS2339: Property responsiveStyle does not exist on type {}
			...meta?.argTypes?.responsiveStyle,
			control: { disable: true }
		}
	}
}
