// Common types and shared values for Storybook stories
// (basically TypeScript versions of the PHP component enums and whatnot)
// and other shared utilities for stories

export const THEME_COLORS = [
	'primary',
	'secondary',
	'accent',
	'error',
	'success',
	'warning',
	'info',
	'light',
	'dark',
	'white'
];

export type ThemeColor = (typeof THEME_COLORS)[number];

export const ALIGNMENT_OPTIONS = [
	"start",
	"end",
	"center",
	"justify",
	"match-parent"
];

export type Alignment = (typeof ALIGNMENT_OPTIONS)[number];

export const CONTAINER_SIZES = [
	"default",
	"wide",
	"fullwidth",
	"narrow",
	"narrower",
	"small"
];

export type ContainerSize = (typeof CONTAINER_SIZES)[number];

export const ORIENTATION_OPTIONS = [
	"horizontal",
	"vertical"
];

export type Orientation = (typeof ORIENTATION_OPTIONS)[number];
