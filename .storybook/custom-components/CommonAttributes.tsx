import React, { useState, useCallback, useEffect } from 'react';
import { ComponentDefinition, ComponentContentDefProps } from './types.ts';

/**
 * Component to fetch and display attributes from the Renderable abstract class's JSON definition file
 * that aren't intended to be editable via the Storybook UI, so they don't need to be included in every story file
 */
export function CommonAttributes() {
	const { specs, loading, error } = useRenderableSpecs();

	const specsToShow = specs?.attributes ? Object.entries(specs?.attributes).filter(([name]) => {
		return !['classes', 'tagName'].includes(name);
	}) : [];

	return specs ? (
		<div className="comet-component-specs">
			<h4>Common attributes</h4>
			<p>While not editable in Storybook, all components also have the below attributes available to be set.</p>
			{/* eslint-disable-next-line max-len */}
			<p>You can also assign any <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Attributes" target="_blank">HTML attributes</a> that are valid for the element if not already covered by the listed options.</p>
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Description and type</th>
					</tr>
				</thead>
				<tbody>
					{specsToShow.map(([name, { description, type }]) => (
						<tr key={name}>
							<td>{name}</td>
							<td>{description} <br/><code>{type}</code></td>
						</tr>
					))}
				</tbody>
			</table>
		</div>
	) : null;
}

function useRenderableSpecs() {
	const [specs, setSpecs] = useState<ComponentDefinition>(null);
	const [loading, setLoading] = useState<boolean>(true);
	const [error, setError] = useState<Error | null>(null);

	const fetchJsonDef = useCallback(async () => {
		if(window.location.hostname.startsWith('storybook.comet-components.test')) {
			return await fetch('https://comet-components.test/packages/core/src/base/components/__docs__/Renderable.json');
		}

		return await fetch('https://cometcomponents.io/packages/core/src/base/components/__docs__/Renderable.json');
	}, []);

	useEffect(() => {
		const fetchData = async () => {
			setLoading(true);
			try {
				const response = await fetchJsonDef();
				const data = await response.json();
				setSpecs(data);
				setError(null);
			}
			catch (e) {
				console.error('Error fetching JSON definition for Renderable abstract class', e);
				setError(e as Error);
				setSpecs(null);
			}
			finally {
				setLoading(false);
			}
		};

		fetchData();
	}, [fetchJsonDef]);

	return { specs, loading, error };
}
