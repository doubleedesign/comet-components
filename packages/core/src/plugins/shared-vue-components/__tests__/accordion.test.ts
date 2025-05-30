import { render, screen } from '@testing-library/vue';
import Accordion from '../accordion.vue';
import userEvent from '@testing-library/user-event';
import { axe } from 'jest-axe';

describe('Accordion', () => {

	it('should not have any accessibility violations', async() => {
		const { container } = render(Accordion, {
			props: {
				panels: [
					{
						summary: { title: { content: 'Panel title 1' }, },
						content: { content: 'Panel content 1' , attributes: { id: "panel-1" } }
					},
					{
						summary: { title: { content: 'Panel title 2' }, },
						content: { content: 'Panel content 2', attributes: { id: "panel-2" } }
					},
				]
			},
		});

		screen.debug();

		const results = await axe(container);

		expect(results).toHaveNoViolations();

	});
});
