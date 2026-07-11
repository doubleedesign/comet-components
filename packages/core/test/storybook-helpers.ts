// Common types and shared values for Storybook stories
// (basically TypeScript versions of the PHP component enums and whatnot)
// and other shared utilities for stories

export {
	type ThemeColor, type Alignment, type ContainerSize, type Orientation,
	ALIGNMENT_OPTIONS, ORIENTATION_OPTIONS, GROUP_LAYOUT_OPTIONS
} from '../dist/types.ts';
import { CONTAINER_SIZE_OPTIONS, THEME_COLOR_OPTIONS } from '../dist/types.ts';

export const CONTAINER_SIZES = CONTAINER_SIZE_OPTIONS;

export const THEME_COLORS = THEME_COLOR_OPTIONS;
