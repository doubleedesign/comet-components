// Jest unit test with vue testing library
import { render, screen } from '@testing-library/vue';
import Tabs from '../tabs.vue';
import userEvent from '@testing-library/user-event';
import { axe } from 'jest-axe';

describe('Tabs', () => {
	it('renders the tabs with the correct titles and content', () => {
		render(Tabs, {
			props: {
				panels: [
					{
						summary: {
							classes: [],
							attributes: {},
							title: {
								content: 'Tab 1',
								classes: [],
								attributes: {}
							}
						},
						content: {
							content: 'This is the content of tab 1.',
							classes: ['tabs__content__tab-panel'],
							attributes: {}
						}
					},
					{
						summary: {
							classes: [],
							attributes: {},
							title: {
								content: 'Tab 2',
								classes: [],
								attributes: {}
							}
						},
						content: {
							content: 'This is the content of tab 2.',
							classes: ['tabs__content__tab-panel'],
							attributes: {}
						}
					},
				]
			},
		});

		expect(screen.getByText('Tab 1')).toBeInTheDocument();
		expect(screen.getByText('Tab 2')).toBeInTheDocument();
		expect(screen.getByText('This is the content of tab 1.')).toBeInTheDocument();
		// Tab 2 content should be in the document but not visible initially
		const tab2Content = screen.getByText('This is the content of tab 2.');
		expect(tab2Content).toBeInTheDocument();
		expect(tab2Content).not.toBeVisible();
	});

	it('switches tabs when a tab title is clicked', async () => {
		render(Tabs, {
			props: {
				panels: [
					{
						summary: {
							classes: [],
							attributes: {},
							title: {
								content: 'Tab 1',
								classes: [],
								attributes: {}
							}
						},
						content: {
							content: 'This is the content of tab 1.',
							classes: ['tabs__content__tab-panel'],
							attributes: {}
						}
					},
					{
						summary: {
							classes: [],
							attributes: {},
							title: {
								content: 'Tab 2',
								classes: [],
								attributes: {}
							}
						},
						content: {
							content: 'This is the content of tab 2.',
							classes: ['tabs__content__tab-panel'],
							attributes: {}
						}
					},
				]
			},
		});

		const tab1Content = screen.getByText('This is the content of tab 1.');
		const tab2Content = screen.getByText('This is the content of tab 2.');
		const tab2Title = screen.getByText('Tab 2');

		// Initially, tab 1 content should be visible and tab 2 content should not
		expect(tab1Content).toBeVisible();
		expect(tab2Content).not.toBeVisible();

		// Click on tab 2
		await userEvent.click(tab2Title);

		// Now tab 2 content should be visible and tab 1 content should not
		expect(tab2Content).toBeVisible();
		expect(tab1Content).not.toBeVisible();
	});

	it('should not have any accessibility violations', async() => {
		const { container } = render(Tabs, {
			props: {
				panels: [
					{
						summary: {
							classes: [],
							attributes: {},
							title: {
								content: 'Tab 1',
								classes: [],
								attributes: {}
							}
						},
						content: {
							content: 'This is the content of tab 1.',
							classes: ['tabs__content__tab-panel'],
							attributes: {}
						}
					},
					{
						summary: {
							classes: [],
							attributes: {},
							title: {
								content: 'Tab 2',
								classes: [],
								attributes: {}
							}
						},
						content: {
							content: 'This is the content of tab 2.',
							classes: ['tabs__content__tab-panel'],
							attributes: {}
						}
					},
				]
			},
		});

		// Run axe on the rendered component
		const results = await axe(container);

		// Assert that there are no accessibility violations
		expect(results).toHaveNoViolations();
	});
});
