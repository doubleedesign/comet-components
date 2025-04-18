import type { FunctionComponent, ReactNode } from 'react';
import React, { useMemo } from 'react';

import type { ComponentTitle, PreparedMeta, Renderer } from 'storybook/internal/types';
import { useOf, type Of } from '@storybook/blocks';
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';
import { VueComponentIcon } from '../custom-components/icon-items/VueComponentIcon.tsx';
import { WordPressAvailableIcon } from '../custom-components/icon-items/WordPressAvailableIcon.tsx';

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
				<VueComponentIcon />
			);
		}

		if (preparedMeta?.tags?.includes('wordpress-block')) {
			icons.push(
				<WordPressAvailableIcon />
			);
		}

		return (
			<ul className="sbdocs__icons">
				{icons.map(((icon, index) => {
					return (
						<li key={index} className="sbdocs__icon">
							{icon}
						</li>
					);
				}))}
			</ul>
		);
	}, [preparedMeta]);


	return (
		<div className="sbdocs-title">
			<h1>{content}</h1>
			{icons}
		</div>
	);
};
