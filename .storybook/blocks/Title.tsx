import type { FunctionComponent, ReactNode } from 'react';
import React, { useMemo } from 'react';

import type { ComponentTitle, PreparedMeta, Renderer } from 'storybook/internal/types';
import { useOf, type Of } from '@storybook/blocks';
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';

interface TitleProps {
	/**
	 * Specify where to get the title from. Must be a CSF file's default export. If not specified, the
	 * title will be read from children, or extracted from the meta of the attached CSF file.
	 */
	of?: Of;

	/** Specify content to display as the title. */
	children?: ReactNode;
}

const STORY_KIND_PATH_SEPARATOR = /\s*\/\s*/;

export const extractTitle = (title: ComponentTitle) => {
	const groups = title.trim().split(STORY_KIND_PATH_SEPARATOR);

	return groups?.[groups?.length - 1] || title;
};

export const Title: FunctionComponent<TitleProps> = (props) => {
	const { children, of } = props;

	if ('of' in props && of === undefined) {
		throw new Error('Unexpected `of={undefined}`, did you mistype a CSF file reference?');
	}

	let preparedMeta: PreparedMeta<Renderer>;
	try {
		preparedMeta = useOf(of || 'meta', ['meta']).preparedMeta;
	}
	catch (error: unknown) {
		console.error(error);
	}

	const content = children || extractTitle(preparedMeta?.title || '');

	const icons = useMemo(() => {
		const icons = [];

		if (preparedMeta?.tags?.includes('vue')) {
			icons.push(
				<Tippy content="Vue-enhanced component" placement="bottom">
					<a className="sbdocs-title__icons__item__link sbdocs-title__icons__item__link--vue"
						href="https://cometcomponents.io/docs/technical-deep-dives/js-architecture/vue.html"
						target="_blank"
					>
						<svg version="1.1" viewBox="0 0 261.76 226.69" xmlns="http://www.w3.org/2000/svg">
							<g transform="matrix(1.3333 0 0 -1.3333 -76.311 313.34)">
								<g transform="translate(178.06 235.01)">
									<path d="m0 0-22.669-39.264-22.669 39.264h-75.491l98.16-170.02 98.16 170.02z" fill="#41b883"/>
								</g>
								<g transform="translate(178.06 235.01)">
									<path d="m0 0-22.669-39.264-22.669 39.264h-36.227l58.896-102.01 58.896 102.01z" fill="#34495e"/>
								</g>
							</g>
						</svg>
					</a>
				</Tippy>
			);
		}

		if (preparedMeta?.tags?.includes('wordpress')) {
			icons.push(
				<Tippy content="Available for WordPress" placement="bottom">
					<a className="sbdocs-title__icons__item__link sbdocs-title__icons__item__link--vue"
						href="https://cometcomponents.io/docs/installation/wordpress.html"
						target="_blank"
					>
						<svg viewBox="0 0 122.52 122.523" xmlns="http://www.w3.org/2000/svg">
							<g fill="#21759b">
								<path d="m8.708 61.26c0 20.802 12.089 38.779 29.619 47.298l-25.069-68.686c-2.916 6.536-4.55 13.769-4.55 21.388z"/>
								{/* eslint-disable-next-line max-len */}
								<path d="m96.74 58.608c0-6.495-2.333-10.993-4.334-14.494-2.664-4.329-5.161-7.995-5.161-12.324 0-4.831 3.664-9.328 8.825-9.328.233 0 .454.029.681.042-9.35-8.566-21.807-13.796-35.489-13.796-18.36 0-34.513 9.42-43.91 23.688 1.233.037 2.395.063 3.382.063 5.497 0 14.006-.667 14.006-.667 2.833-.167 3.167 3.994.337 4.329 0 0-2.847.335-6.015.501l19.138 56.925 11.501-34.493-8.188-22.434c-2.83-.166-5.511-.501-5.511-.501-2.832-.166-2.5-4.496.332-4.329 0 0 8.679.667 13.843.667 5.496 0 14.006-.667 14.006-.667 2.835-.167 3.168 3.994.337 4.329 0 0-2.853.335-6.015.501l18.992 56.494 5.242-17.517c2.272-7.269 4.001-12.49 4.001-16.989z"/>
								{/* eslint-disable-next-line max-len */}
								<path d="m62.184 65.857-15.768 45.819c4.708 1.384 9.687 2.141 14.846 2.141 6.12 0 11.989-1.058 17.452-2.979-.141-.225-.269-.464-.374-.724z"/>
								{/* eslint-disable-next-line max-len */}
								<path d="m107.376 36.046c.226 1.674.354 3.471.354 5.404 0 5.333-.996 11.328-3.996 18.824l-16.053 46.413c15.624-9.111 26.133-26.038 26.133-45.426.001-9.137-2.333-17.729-6.438-25.215z"/>
								{/* eslint-disable-next-line max-len */}
								<path d="m61.262 0c-33.779 0-61.262 27.481-61.262 61.26 0 33.783 27.483 61.263 61.262 61.263 33.778 0 61.265-27.48 61.265-61.263-.001-33.779-27.487-61.26-61.265-61.26zm0 119.715c-32.23 0-58.453-26.223-58.453-58.455 0-32.23 26.222-58.451 58.453-58.451 32.229 0 58.45 26.221 58.45 58.451 0 32.232-26.221 58.455-58.45 58.455z"/>
							</g>
						</svg>
					</a>
				</Tippy>
			);
		}

		return (
			<ul className="sbdocs-title__icons">
				{icons.map((icon => {
					return (
						<li className="sbdocs-title__icons__item" key={icon.key}>
							{icon}
						</li>
					);
				}))}
			</ul>
		);
	}, [preparedMeta]);


	return content ? <h1 className="sbdocs-title">{content} {icons}</h1> : null;
};
