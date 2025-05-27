import React, { useState, useCallback, useMemo, useEffect } from 'react';
import { useResize } from './useResize.tsx';
import { ArrowTopRightIcon } from '@storybook/icons';

// A wrapper for the primary story on Docs pages to provide rudimentary viewport changes
export const ResponsiveContainer = ({ children }) => {
	const wrapperRef = React.useRef<HTMLDivElement>(null);
	const { width } = useResize(wrapperRef, []);
	const [viewportWidth, setViewportWidth] = useState('100%');

	const options = useMemo(() => {
		// Filter out widths wider than the current available space
		return [1800, 1440, 1280, 1140, 1024, 768, 640, 400].filter(thisWidth => {
			return thisWidth <= width;
		});
	}, [width]);

	const handleChange = useCallback((event) => {
		if(event.target.value === '100%') {
			setViewportWidth('100%');
		}
		else {
			const newWidth = parseInt(event.target.value, 10);
			setViewportWidth(`${newWidth}px`);
		}
		// Trigger a resize event so inner component JS will pick it up where applicable
		const resizeEvent = new Event('resize');
		window.dispatchEvent(resizeEvent);
	}, [setViewportWidth]);

	useEffect(() => {
		if(viewportWidth === '100%') return;

		// If the currently set viewport width is wider than the available space, reset it to 100%
		const currentSelection = parseInt(viewportWidth, 10);
		if(currentSelection > width) {
			setViewportWidth('100%');
		}
	}, [width, viewportWidth, setViewportWidth]);

	return (
		<div ref={wrapperRef} className="docs-story-responsive-container">
			<div style={{ maxWidth: viewportWidth }} className="docs-story-responsive-container__inner">
				<div className="docs-story-viewport-width-control">
					<label htmlFor="viewportWidthSelect">Preview width:</label>
					<select id="viewportWidthSelect" onChange={handleChange} defaultValue={'100%'}>
						<option value="100%">100%</option>
						{options.map((thisWidth) => {
							return (
								<option key={thisWidth} value={thisWidth}>
									{thisWidth}px
								</option>
							);
						})}
					</select>
				</div>
				{children}
				<div className="docs-story-open-in-new-tab">
					<OpenInNewTabButton />
				</div>
			</div>
		</div>
	);
};

function OpenInNewTabButton() {
	const cachedUrl = localStorage.getItem('storyUrlWithParams');

	return (
		<a href={cachedUrl} target="_blank">
			Open in a new tab
			<ArrowTopRightIcon/>
		</a>
	);
}
