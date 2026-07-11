import type { Meta, StoryObj } from '@storybook/html-vite';
import { createStoryBase } from "../../../../test/story-base.ts";
import { within, expect, waitFor } from '@storybook/test';
import { TabsProps } from '../../../../dist/types';

const meta = {
	title: 'Layout/Tabs',
	tags: ['vue', 'wordpress-block', 'autodocs'],
	...(await createStoryBase('Tabs')),
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
	tags: ['!dev']
};
