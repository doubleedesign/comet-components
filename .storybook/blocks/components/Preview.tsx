//@source https://github.com/storybookjs/storybook/blob/next/code/lib/blocks/src/components/Preview.tsx
// Copied as part of the overrides needed to load a custom Source component
// And also modified to add the story name to the HTML for customising output wrapper styling for specific stories
import type { ClipboardEvent, FC, ReactElement, ReactNode, Context } from 'react';
import React, { Children, useCallback, useState, createContext } from 'react';
import { ActionBar, Zoom, type ActionItem, Loader } from 'storybook/internal/components';
import type { ModuleExport } from 'storybook/internal/types';
import { styled, Theme } from 'storybook/internal/theming';
import { darken } from 'polished';
import { Toolbar } from './Toolbar.tsx';
import { Source, type SourceProps } from '../../custom-components/Source.tsx'; // this is my custom one

const StorySkeleton = () => <Loader/>;

// Copied from https://github.com/storybookjs/storybook/blob/next/code/lib/blocks/src/components/ZoomContext.tsx
const ZoomContext: Context<{ scale: number }> = createContext({
	scale: 1,
});

// Copied from https://github.com/storybookjs/storybook/blob/next/code/lib/blocks/src/components/BlockBackgroundStyles.tsx
const getBlockBackgroundStyle: (theme: Theme) => object = (theme: Theme) => ({
	borderRadius: theme.appBorderRadius,
	background: theme.background.content,
	boxShadow:
		theme.base === 'light' ? 'rgba(0, 0, 0, 0.10) 0 1px 3px 0' : 'rgba(0, 0, 0, 0.20) 0 2px 5px 0',
	border: `1px solid ${theme.appBorderColor}`,
});

export interface PreviewProps {
	isLoading?: true;
	layout?: Layout;
	isColumn?: boolean;
	columns?: number;
	withSource?: SourceProps;
	isExpanded?: boolean;
	withToolbar?: boolean;
	className?: string;
	additionalActions?: ActionItem[];
	children?: ReactNode;
	title?: string; // I added this
	of?: ModuleExport; // and this
}

export type Layout = 'padded' | 'fullscreen' | 'centered';

const ChildrenContainer = styled.div<PreviewProps & { layout: Layout }>(
	({ isColumn, columns, layout }) => ({
		display: isColumn || !columns ? 'block' : 'flex',
		position: 'relative',
		flexWrap: 'wrap',
		overflow: 'auto',
		flexDirection: isColumn ? 'column' : 'row',

		'& .innerZoomElementWrapper > *': isColumn
			? {
					width: layout !== 'fullscreen' ? 'calc(100% - 20px)' : '100%',
					display: 'block',
				}
			: {
					maxWidth: layout !== 'fullscreen' ? 'calc(100% - 20px)' : '100%',
					display: 'inline-block',
				},
	}),
	({ layout = 'padded' }) =>
		layout === 'centered' || layout === 'padded'
			? {
					padding: '30px 20px',
					'& .innerZoomElementWrapper > *': {
						width: 'auto',
						border: '10px solid transparent!important',
					},
				}
			: {},
	({ layout = 'padded' }) =>
		layout === 'centered'
			? {
					display: 'flex',
					justifyContent: 'center',
					justifyItems: 'center',
					alignContent: 'center',
					alignItems: 'center',
				}
			: {},
	({ columns }) =>
		columns && columns > 1
			? { '.innerZoomElementWrapper > *': { minWidth: `calc(100% / ${columns} - 20px)` } }
			: {}
);

const StyledSource = styled(Source)(({ theme }) => ({
	margin: 0,
	borderTopLeftRadius: 0,
	borderTopRightRadius: 0,
	borderBottomLeftRadius: theme.appBorderRadius,
	borderBottomRightRadius: theme.appBorderRadius,
	border: 'none',

	background:
		theme.base === 'light' ? 'rgba(0, 0, 0, 0.85)' : darken(0.05, theme.background.content),
	color: theme.color.lightest,
	button: {
		background:
			theme.base === 'light' ? 'rgba(0, 0, 0, 0.85)' : darken(0.05, theme.background.content),
	},
}));

const PreviewContainer = styled.div<PreviewProps>(
	({ theme, withSource, isExpanded }) => ({
		position: 'relative',
		overflow: 'hidden',
		margin: '25px 0 40px',
		...getBlockBackgroundStyle(theme),
		borderBottomLeftRadius: withSource && isExpanded && 0,
		borderBottomRightRadius: withSource && isExpanded && 0,
		borderBottomWidth: isExpanded && 0,

		'h3 + &': {
			marginTop: '16px',
		},
	}),
	({ withToolbar }) => withToolbar && { paddingTop: 40 }
);

interface SourceItem {
	source?: ReactElement;
	actionItem: ActionItem;
}

const getSource = (
	title: string, // I added this
	of: ModuleExport, // and this
	withSource: SourceProps,
	expanded: boolean,
	setExpanded: Function,
): SourceItem => {
	switch (true) {
		case !!(withSource && withSource.error): {
			return {
				source: null,
				actionItem: {
					title: 'No code available',
					className: 'docblock-code-toggle docblock-code-toggle--disabled',
					disabled: true,
					onClick: () => setExpanded(false),
				},
			};
		}
		case expanded: {
			return {
				source: <StyledSource title={title} of={of} {...withSource} />,
				actionItem: {
					title: 'Hide code',
					className: 'docblock-code-toggle docblock-code-toggle--expanded',
					onClick: () => setExpanded(false),
				},
			};
		}
		default: {
			return {
				source: <StyledSource title={title} of={of} {...withSource} />,
				actionItem: {
					title: 'Show code',
					className: 'docblock-code-toggle',
					onClick: () => setExpanded(true),
				},
			};
		}
	}
};
function getStoryId(children: ReactNode) {
	if (Children.count(children) === 1) {
		const elt = children as ReactElement;
		if (elt.props) {
			return elt.props.id;
		}
	}

	return null;
}

const PositionedToolbar = styled(Toolbar)({
	position: 'absolute',
	top: 0,
	left: 0,
	right: 0,
	height: 40,
});

const Relative = styled.div({
	overflow: 'hidden',
	position: 'relative',
});

/**
 * A preview component for showing one or more component `Story` items. The preview also shows the
 * source for the component as a drop-down.
 * This has been customised to pass story data down to a custom Source component.
 */
export const Preview: FC<PreviewProps> = ({
	isLoading,
	isColumn,
	columns,
	children,
	withSource,
	withToolbar = false,
	isExpanded = false,
	additionalActions,
	className,
	layout = 'padded',
	title,
	of,
	...props
}) => {
	const [expanded, setExpanded] = useState(isExpanded);
	const { source, actionItem } = getSource(title, of, withSource, expanded, setExpanded);
	const [scale, setScale] = useState(1);
	const previewClasses = [className].concat(['sbdocs', 'sbdocs-preview', 'sb-unstyled']);

	const defaultActionItems = withSource ? [actionItem] : [];
	const [additionalActionItems, setAdditionalActionItems] = useState(
		additionalActions ? [...additionalActions] : []
	);
	const actionItems = [...defaultActionItems, ...additionalActionItems];

	const { window: globalWindow } = globalThis;

	const copyToClipboard = useCallback(async (text: string) => {
		const { createCopyToClipboardFunction } = await import('storybook/internal/components');
		createCopyToClipboardFunction();
	}, []);

	const onCopyCapture = (e: ClipboardEvent<HTMLInputElement>) => {
		// When the selection range is neither empty nor collapsed, we can assume
		// user's intention is to copy the selected text, instead of the story's
		// code snippet.
		const selection: Selection | null = globalWindow.getSelection();
		if (selection && selection.type === 'Range') {
			return;
		}

		e.preventDefault();
		if (additionalActionItems.filter((item) => item.title === 'Copied').length === 0) {
			copyToClipboard(source.props.code).then(() => {
				setAdditionalActionItems([
					...additionalActionItems,
					{
						title: 'Copied',
						onClick: () => {},
					},
				]);
				globalWindow.setTimeout(
					() =>
						setAdditionalActionItems(
							additionalActionItems.filter((item) => item.title !== 'Copied')
						),
					1500
				);
			});
		}
	};

	return (
		<PreviewContainer
			{...{ withSource, withToolbar }}
			{...props}
			className={previewClasses.join(' ')}
		>
			{withToolbar && (
				<PositionedToolbar
					isLoading={isLoading}
					border
					zoom={(z: number) => setScale(scale * z)}
					resetZoom={() => setScale(1)}
					storyId={getStoryId(children)}
					baseUrl="./iframe.html"
				/>
			)}
			<ZoomContext.Provider value={{ scale }}>
				<Relative className="docs-story" onCopyCapture={withSource && onCopyCapture}>
					<ChildrenContainer
						isColumn={isColumn || !Array.isArray(children)}
						columns={columns}
						layout={layout}
					>
						<Zoom.Element scale={scale}>
							{Array.isArray(children) ? (
								children.map((child, i) => <div key={i}>{child}</div>)
							) : (
								<div>{children}</div>
							)}
						</Zoom.Element>
					</ChildrenContainer>
					<ActionBar actionItems={actionItems} />
				</Relative>
			</ZoomContext.Provider>
			{withSource && expanded && source}
		</PreviewContainer>
	);
};

const StyledPreview = styled(Preview)(() => ({
	'.docs-story': {
		paddingTop: 32,
		paddingBottom: 40,
	},
}));

export const PreviewSkeleton = () => (
	<StyledPreview isLoading withToolbar>
		<StorySkeleton />
	</StyledPreview>
);
