import React, { useState, useCallback, useEffect } from 'react';
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';
import { PhpCodeBlock } from './PhpCodeBlock.tsx';
import { ArrowTopRightIcon } from '@storybook/icons';
import { ComponentDefinition, ComponentContentDefProps } from './types.ts';

/**
 * Component to fetch and display description and usage information directly from the component's JSON definition file
 * @param componentName - PascalCase name of the component to fetch specs for
 */
export function ComponentSpecs({ componentName }) {
	const { specs, cssInfo } = useComponentSpecs(componentName);
	if (!specs) return;

	let belongsInside = Array.isArray(specs?.belongsInside)
		? `Inside ${list_to_string_with_oxford_comma(specs?.belongsInside, 'and/or')}`
		: `Inside ${specs?.belongsInside}`;
	if(specs?.belongsInside === 'LayoutComponent') {
		belongsInside = 'Inside any structure or layout component';
	}
	if(!specs?.belongsInside) {
		if(componentName === 'Container') {
			belongsInside = 'At the top level of the document, and within SiteHeader, SiteFooter, Banner, and PageHeader components';
		}
		else {
			belongsInside = 'At the top level of the document';
		}
	}
	// Some specific overrides
	if(componentName === 'Banner') {
		belongsInside = 'At the top level of the document or inside a Container component';
	}
	if(componentName === 'Breadcrumbs') {
		belongsInside = 'Inside any structure or layout component and inside the PageHeader component';
	}

	let exampleCode = '// TODO: This needs an example!';
	if(specs?.content) {
		exampleCode = `$component = new ${specs.name}($attributes, $content);\n$component->render();`;
	}
	if(specs?.innerComponents) {
		exampleCode = `$component = new ${specs.name}($attributes, $innerComponents);\n$component->render();`;
	}
	if(['Image', 'DateBlock', 'DateRangeBlock'].includes(componentName)) {
		exampleCode = `$component = new ${specs.name}($attributes);\n$component->render();`;
	}
	if(componentName === 'Breadcrumbs') {
		exampleCode = `$component = new ${specs.name}($attributes, $breadcrumbs);\n$component->render();`;
	}

	let contentType = '';
	const strippedContentType = specs?.innerComponents?.type?.replace('array<', '').replace('>', '');
	if(strippedContentType) {
		if(strippedContentType === 'Renderable') {
			contentType = ' can be of any component type that extends the Renderable abstract class';
		}
		else {
			const multipleContentTypes = strippedContentType.split('|');
			contentType = ` should be ${list_to_string_with_oxford_comma(multipleContentTypes, 'and/or')} components`;
		}
	}
	else if(specs?.data) {
		contentType = (specs?.data as ComponentContentDefProps)?.description;
	}
	else if(specs?.files) {
		contentType = (specs?.files as ComponentContentDefProps)?.description;
	}
	if(specs?.breadcrumbs) {
		if(specs?.content) {
			contentType = ' + ' + (specs?.breadcrumbs as ComponentContentDefProps)?.description;
		}
		else {
			contentType = (specs?.breadcrumbs as ComponentContentDefProps)?.description;
		}
	}

	return (
		<div className="comet-component-specs">
			<p dangerouslySetInnerHTML={{ __html: specs?.description }}/>
			<table>
				<tbody>
					<tr>
						<th scope="row">
							Extends
							<Tippy content="The foundational PHP class this component is built upon" placement="bottom-start">
								<a href="https://cometcomponents.io/docs/technical-deep-dives/php-architecture/abstract-classes.html">
									<i className="fa-solid fa-circle-question"></i>
								</a>
							</Tippy>
						</th>
						<td><p>{specs?.extends}</p></td>
					</tr>
					<tr>
						<th scope="row">
							Belongs
							<Tippy content="Usage contexts this component is designed and tested for" placement="bottom-start">
								<a href="#"><i className="fa-solid fa-circle-question"></i></a>
							</Tippy>
						</th>
						<td>
							<p>{belongsInside}</p>
							{(cssInfo?.containerQueries && componentName !== 'Banner') && (
								<p>
									{/* eslint-disable-next-line max-len */}
									<strong>Note: </strong>This component's default CSS utilises <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_containment/Container_queries" target="_blank">container queries</a>.
									It should be placed inside a suitable containment context to achieve the expected layout behaviour.
									By default, the <code>Container</code> component provides this.
								</p>
							)}
						</td>
					</tr>
					{(specs?.innerComponents || specs?.content) &&<tr>
						<th scope="row">{specs?.innerComponents ? 'Inner components' : 'Content'}</th>
						<td>
							<p>
								{specs?.innerComponents?.description ?? specs?.content?.description} {contentType}
							</p>
						</td>
					</tr>}
				</tbody>
			</table>

			<div className="comet-component-code-preview-wrapper">
				<h3>Basic usage</h3>
				<p><a href="https://cometcomponents.io/docs/usage/overview.html" target="_blank">More about usage syntax <ArrowTopRightIcon/></a></p>
				<PhpCodeBlock codeString={exampleCode}/>
			</div>
		</div>
	);

}

function useComponentSpecs(componentName: string) {
	const [specs, setSpecs] = useState<ComponentDefinition>(null);
	const [cssInfo, setCssInfo] = useState<Record<string, boolean>>(null);
	const [loading, setLoading] = useState<boolean>(true);
	const [error, setError] = useState<Error | null>(null);

	const fetchJsonDef = useCallback(async () => {
		if(window.location.hostname.startsWith('storybook.comet-components.test')) {
			return await fetch(`https://comet-components.test/packages/core/src/components/${componentName}/__docs__/${componentName}.json`);
		}

		return await fetch(`https://cometcomponents.io/packages/core/src/components/${componentName}/__docs__/${componentName}.json`);
	}, [componentName]);

	const fetchCss = useCallback(async () => {
		if(window.location.hostname.startsWith('storybook.comet-components.test')) {
			return await fetch(`https://comet-components.test/packages/core/src/components/${componentName}/${componentName}.css`);
		}

		return await fetch(`https://cometcomponents.io/packages/core/src/components/${componentName}/${componentName}.css`);
	}, [componentName]);

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
				console.error('Error fetching JSON definition for component ', componentName, e);
				setError(e as Error);
				setSpecs(null);
			}
			finally {
				setLoading(false);
			}
		};

		const fetchCssData = async () => {
			setLoading(true);
			try {
				const response = await fetchCss();
				const data = await response.text();
				// Does the text contain @container?
				if(data.includes('@container')) {
					setCssInfo({
						containerQueries: true
					});
				}
				setError(null);
			}
			catch (e) {
				console.error('Error fetching CSS definition for component ', componentName, e);
				setError(e as Error);
				setCssInfo(null);
			}
			finally {
				setLoading(false);
			}
		};

		fetchData();
		fetchCssData();
	}, [componentName, fetchJsonDef]);

	return { specs, cssInfo, loading, error };
}

function list_to_string_with_oxford_comma(list: string[], lastJoinword = 'and') {
	if(list.length === 0) return '';
	if(list.length === 1) return list[0];
	if(list.length === 2) return `${list[0]} ${lastJoinword} ${list[1]}`;

	return `${list.slice(0, -1).join(', ')}, ${lastJoinword} ${list[list.length - 1]}`;
}
