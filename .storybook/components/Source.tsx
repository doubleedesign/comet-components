import React from 'react';
import type { FunctionComponent } from 'react';
import type { SupportedLanguage } from 'storybook/internal/components';
import {
	ignoreSsrWarning,
	styled,
} from 'storybook/internal/theming';


export enum SourceError {
	NO_STORY = 'There\u2019s no story here.',
	SOURCE_UNAVAILABLE = 'Oh no! The source is not available.',
}

export interface SourceProps {
	language?: SupportedLanguage;
	isLoading?: boolean;
	error?: SourceError;
}

const SourceSkeletonWrapper = styled.div(({ theme }) => ({
	background: theme.background.content,
	borderRadius: theme.appBorderRadius,
	border: `1px solid ${theme.appBorderColor}`,
	boxShadow:
		theme.base === 'light' ? 'rgba(0, 0, 0, 0.10) 0 1px 3px 0' : 'rgba(0, 0, 0, 0.20) 0 2px 5px 0',
	margin: '25px 0 40px',
	padding: '20px 20px 20px 22px',
}));

const SourceSkeletonPlaceholder = styled.div(({ theme }) => ({
	animation: `${theme.animation.glow} 1.5s ease-in-out infinite`,
	background: theme.appBorderColor,
	height: 17,
	marginTop: 1,
	width: '60%',

	[`&:first-child${ignoreSsrWarning}`]: {
		margin: 0,
	},
}));

const SourceSkeleton = () => (
	<SourceSkeletonWrapper>
		<SourceSkeletonPlaceholder />
		<SourceSkeletonPlaceholder style={{ width: '80%' }} />
		<SourceSkeletonPlaceholder style={{ width: '30%' }} />
		<SourceSkeletonPlaceholder style={{ width: '80%' }} />
	</SourceSkeletonWrapper>
);

/** Syntax-highlighted source code for a component (or anything!) */
const Source: FunctionComponent<SourceProps> = ({
	isLoading,
	error,
	...rest
}) => {
	if (isLoading) {
		return <SourceSkeleton />;
	}
	if (error) {
		return <>{error}</>;
	}

	return (
		<>MY CODE HERE</>
	);
};

export { Source };
