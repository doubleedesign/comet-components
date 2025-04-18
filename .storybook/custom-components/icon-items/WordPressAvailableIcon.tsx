import React from 'react';
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';
import { ComponentIconProps } from './types.ts';

export function WordPressAvailableIcon({ tooltipPosition = 'bottom', asLink = true, tooltip = 'Available as a WordPress block' }: ComponentIconProps) {
	const icon = (
		<svg viewBox="0 0 122.52 122.523" xmlns="http://www.w3.org/2000/svg">
			<g fill="#21759b">
				<path d="m8.708 61.26c0 20.802 12.089 38.779 29.619 47.298l-25.069-68.686c-2.916 6.536-4.55 13.769-4.55 21.388z"/>
				{/* eslint-disable-next-line max-len */}
				<path d="m96.74 58.608c0-6.495-2.333-10.993-4.334-14.494-2.664-4.329-5.161-7.995-5.161-12.324 0-4.831 3.664-9.328 8.825-9.328.233 0 .454.029.681.042-9.35-8.566-21.807-13.796-35.489-13.796-18.36 0-34.513 9.42-43.91 23.688 1.233.037 2.395.063 3.382.063 5.497 0 14.006-.667 14.006-.667 2.833-.167 3.167 3.994.337 4.329 0 0-2.847.335-6.015.501l19.138 56.925 11.501-34.493-8.188-22.434c-2.83-.166-5.511-.501-5.511-.501-2.832-.166-2.5-4.496.332-4.329 0 0 8.679.667 13.843.667 5.496 0 14.006-.667 14.006-.667 2.835-.167 3.168 3.994.337 4.329 0 0-2.853.335-6.015.501l18.992 56.494 5.242-17.517c2.272-7.269 4.001-12.49 4.001-16.989z"/>
				{ }
				<path d="m62.184 65.857-15.768 45.819c4.708 1.384 9.687 2.141 14.846 2.141 6.12 0 11.989-1.058 17.452-2.979-.141-.225-.269-.464-.374-.724z"/>
				{/* eslint-disable-next-line max-len */}
				<path d="m107.376 36.046c.226 1.674.354 3.471.354 5.404 0 5.333-.996 11.328-3.996 18.824l-16.053 46.413c15.624-9.111 26.133-26.038 26.133-45.426.001-9.137-2.333-17.729-6.438-25.215z"/>
				{/* eslint-disable-next-line max-len */}
				<path d="m61.262 0c-33.779 0-61.262 27.481-61.262 61.26 0 33.783 27.483 61.263 61.262 61.263 33.778 0 61.265-27.48 61.265-61.263-.001-33.779-27.487-61.26-61.265-61.26zm0 119.715c-32.23 0-58.453-26.223-58.453-58.455 0-32.23 26.222-58.451 58.453-58.451 32.229 0 58.45 26.221 58.45 58.451 0 32.232-26.221 58.455-58.45 58.455z"/>
			</g>
		</svg>
	);

	return asLink ? (
		<Tippy content={tooltip} placement={tooltipPosition}>
			<a className="sbdocs__icon__inner sbdocs__icon__inner--wordpress"
				href="https://cometcomponents.io/docs/installation/wordpress.html"
				target="_blank"
			>
				{icon}
			</a>
		</Tippy>
	) : (
		<Tippy content={tooltip} placement={tooltipPosition}>
			<span tabIndex={0} className="sbdocs__icon__item sbdocs__icon__item--wordpress">
				{icon}
			</span>
		</Tippy>
	);
}
