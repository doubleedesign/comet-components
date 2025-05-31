import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { Orientation, ORIENTATION_OPTIONS, ThemeColor, THEME_COLORS } from '../../../../test/storybook-helpers.ts';
import { within, expect, waitFor } from '@storybook/test';

type TabsProps = {
	tagName: 'div';
	colorTheme: ThemeColor;
	orientation: Orientation;
	classes?: string[];
}

const meta = {
	title: 'Layout/Tabs',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...createStoryBase('Tabs'),
	args: {
		tagName: "div",
		colorTheme: "primary",
		orientation: "vertical"
	},
	argTypes: {
		tagName: {
			description: "The HTML tag to use for this component",
			control: false,
			table: {
				defaultValue: {
					summary: "div"
				},
				type: {
					summary: "Tag"
				}
			},
			options: [
				"div"
			]
		},
		orientation: {
			description: "Orientation of the component content, if applicable",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: "vertical"
				},
				type: {
					summary: "Orientation"
				}
			},
			options: ORIENTATION_OPTIONS
		},
		colorTheme: {
			description: "Colour keyword for the fill or outline colour",
			control: {
				type: "select"
			},
			table: {
				defaultValue: {
					summary: ""
				},
				type: {
					summary: "ThemeColor"
				}
			},
			options: THEME_COLORS
		},
		classes: {
			description: "CSS classes",
			control: false,
			table: {
				defaultValue: {
					summary: "tabs"
				},
				type: {
					summary: "array<string>"
				}
			}
		},

	},
	play: async (context) => {
		const { canvasElement, step, userEvent } = context;
		const canvas = within(canvasElement);

		await step('Wait for the component to render', async () => {
			// Wait for the Vue component to be fully rendered
			await waitFor(() => {
				const tablist = canvas.getByRole('tablist');
				expect(tablist).toBeInTheDocument();
			}, {
				timeout: 7000,
				interval: 200
			})
		});

		await step('First tab is active and only its content is shown', async () => {
			const tabs = canvas.getAllByRole('tab');
			expect(tabs[0]).toHaveAttribute('aria-selected', 'true');
			expect(tabs[1]).toHaveAttribute('aria-selected', 'false');

			const tabPanels = canvas.getAllByRole('tabpanel');
			await expect(tabPanels[0]).toBeVisible();
			await expect(tabPanels[1]).not.toBeVisible();
		});

		await step('Switch to second tab on click', async () => {
			const tabs = canvas.getAllByRole('tab');

			await userEvent.click(tabs[1]);

			expect(tabs[1]).toHaveAttribute('aria-selected', 'true');
			expect(tabs[0]).toHaveAttribute('aria-selected', 'false');

			const tabPanels = canvas.getAllByRole('tabpanel');
			await expect(tabPanels[1]).toBeVisible();
			await expect(tabPanels[0]).not.toBeVisible();
		});
	},
} satisfies Meta<TabsProps>;

export default meta;
type Story = StoryObj<TabsProps>;

export const Playground: Story = {
	tags: []
};
