export type Tag = keyof HTMLElementTagNameMap;
	
export const ALIGNMENT_OPTIONS = ['start', 'end', 'center', 'justify', 'match-parent'];
	
export const ASPECT_RATIO_OPTIONS = ['4:3', '3:4', '1:1', '16:9', '9:16', '3:2', '2:3', '21:9', '2.35:1'];
	
export const CONTAINER_SIZE_OPTIONS = ['fullwidth', 'wide', 'narrow', 'narrower', 'small', 'contained'];
	
export const GROUP_LAYOUT_OPTIONS = ['list', 'grid', 'inline'];
	
export const ORIENTATION_OPTIONS = ['horizontal', 'vertical'];
	
export const THEME_COLOR_OPTIONS = ['primary', 'secondary', 'accent', 'error', 'success', 'warning', 'info', 'light', 'dark', 'white', 'black'];
	
export type Alignment = 'start' | 'end' | 'center' | 'justify' | 'match-parent';
	
export type AspectRatio = '4:3' | '3:4' | '1:1' | '16:9' | '9:16' | '3:2' | '2:3' | '21:9' | '2.35:1';
	
export type ContainerSize = 'fullwidth' | 'wide' | 'narrow' | 'narrower' | 'small' | 'contained';
	
export type GroupLayout = 'list' | 'grid' | 'inline';
	
export type Orientation = 'horizontal' | 'vertical';
	
export type ThemeColor = 'primary' | 'secondary' | 'accent' | 'error' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'white' | 'black';
	
	
type ThemeGradient =  {
	from: ThemeColor;
	to: ThemeColor;
};
	
type BackgroundCollection = any;
	
type WithColorPair =  {
	backgroundColor: ThemeColor|ThemeGradient;
	colorTheme: ThemeColor;
};
	
type WithIcon =  {
	iconPrefix: string;
	icon: string;
};
	
type WithImageCropProperties =  {
	aspectRatio: AspectRatio;
	originalImageOrientation: Orientation;
	focalPoint: { x: number; y: number };
	offset: { x: number; y: number };
};
	
type WithLayoutAlignment =  {
	hAlign: Alignment;
	vAlign: Alignment;
};
	
type WithContainerSize =  {
	size: ContainerSize;
};
	
type WithNestedState =  {
	isNested: boolean;
};
	
	
type ContentImageComponent = ImageComponent & {
	align: string;
	aspectRatio: AspectRatio;
	caption: string;
};
	
type DateComponent = Renderable & {
	colorTheme: ThemeColor;
	context: string;
	locale: string;
	shortName: string;
	showDay: boolean;
	showYear: boolean;
};
	
type ImageComponent = Renderable & {
	alt: string;
	context: string;
	shortName: string;
	src: string;
	styleName: string;
	title: string;
};
	
type LayoutComponent = UIComponent & WithLayoutAlignment & WithContainerSize;
	
type PanelComponent = UIComponent & {
	backgroundColor: ThemeColor|ThemeGradient;
	subtitle: string;
	title: string;
};
	
type PanelGroupComponent = UIComponent & {
	backgroundColors: BackgroundCollection;
	colorTheme: ThemeColor;
	orientation: Orientation;
};
	
type Renderable =  {
	classes: string[];
	id: string;
	style: Record<string, string>;
	tagName: Tag;
	testId: string;
};
	
type TextElement = Renderable & {
	textAlign: Alignment;
};
	
type TextElementExtended = TextElement & {
	context: string;
	shortName: string;
};
	
type UIComponent = Renderable & {
	context: string;
	shortName: string;
};
	
	
export type AccordionPanelProps = PanelComponent;
	
export type AccordionProps = PanelGroupComponent & WithIcon & WithContainerSize & WithNestedState & {
	beforeComponents: Renderable[];
};
	
export type BannerProps = LayoutComponent & WithColorPair & WithLayoutAlignment & WithContainerSize & {
	backgroundOpacity: number;
	backgroundType: string;
	contentMaxWidth: number;
	imageProps: array;
};
	
export type BreadcrumbsProps = UIComponent;
	
export type ButtonProps = Renderable & {
	colorTheme: ThemeColor;
	context: string;
	href: string;
	iconAfter: string;
	iconBefore: string;
	isOutline: boolean;
	shortName: string;
};
	
export type ButtonGroupProps = UIComponent & WithLayoutAlignment & {
	colorTheme: ThemeColor;
	orientation: Orientation;
};
	
export type CalloutProps = UIComponent & WithIcon & {
	colorTheme: ThemeColor;
};
	
export type CallToActionProps = LayoutComponent & WithColorPair & WithLayoutAlignment & WithContainerSize;
	
export type CardProps = UIComponent & WithColorPair & WithImageCropProperties & WithNestedState & {
	aboveContentComponents: Renderable[];
	bodyComponents: Renderable[];
	bodyText: string;
	cardAsLink: boolean;
	heading: string;
	image: { src: string; alt: string };
	image_scale: string;
	link: { href: string; content: string; target: string; isOutline: false };
	orientation: Orientation;
	withWrapper: boolean;
};
	
export type CardListProps = LayoutComponent & WithColorPair & WithNestedState & WithLayoutAlignment & WithContainerSize & {
	heading: string;
	layout: GroupLayout;
	link: array;
	maxPerRow: number;
};
	
export type ColumnProps = UIComponent & WithLayoutAlignment & {
	backgroundColor: ThemeColor|ThemeGradient;
};
	
export type ColumnsProps = LayoutComponent & WithLayoutAlignment & WithContainerSize & {
	allowStacking: boolean;
	backgroundColor: ThemeColor|ThemeGradient;
	columnLayout: string;
	qty: number;
};
	
export type ContainerProps = UIComponent & WithContainerSize;
	
export type ContentImageAdvancedProps = ContentImageComponent & WithImageCropProperties;
	
export type ContentImageBasicProps = ContentImageComponent & {
	height: string;
	href: string;
	orientation: Orientation;
	scale: string;
	width: string;
};
	
export type CopyProps = UIComponent & WithColorPair & WithContainerSize & WithNestedState;
	
export type CoverImageProps = Renderable & WithImageCropProperties & {
	alt: string;
	context: string;
	isParallax: boolean;
	shortName: string;
	src: string;
};
	
export type DateBlockProps = DateComponent & {
	date: DateTime;
};
	
export type DateRangeBlockProps = DateComponent & {
	endDate: DateTime|string;
	startDate: DateTime|string;
};
	
export type DetailsProps = UIComponent & {
	summary: string;
};
	
export type EventCardProps = UIComponent & {
	dateComponent: DateBlock|DateRangeBlock;
	detailUrl: string;
	externalLink: { url: string; label: string; target: string };
	location: string;
	name: string;
};
	
export type EventListProps = CardList & WithColorPair & WithNestedState & WithLayoutAlignment & WithContainerSize;
	
export type FileProps = Renderable & WithIcon & {
	colorTheme: ThemeColor;
	context: string;
	description: string;
	mimeType: string|mixed;
	shortName: string;
	size: string;
	title: string;
	uploadDate: string;
	url: string;
};
	
export type FileGroupProps = LayoutComponent & WithColorPair & WithNestedState & WithLayoutAlignment & WithContainerSize & {
	heading: string;
};
	
export type GalleryProps = LayoutComponent & WithNestedState & WithLayoutAlignment & WithContainerSize & {
	backgroundColor: ThemeColor|ThemeGradient;
	caption: string;
	captionAsHeading: boolean;
	imageCrop: boolean;
	lightbox: boolean;
	maxPerRow: number;
};
	
export type GroupProps = UIComponent & WithColorPair & WithNestedState & {
	dataAttrs: Record<string, string>;
};
	
export type HeadingProps = TextElementExtended & {
	level: number;
};
	
export type IconLinksProps = UIComponent & WithLayoutAlignment & {
	iconPrefix: string;
	links: { url: string; label: string; icon: string[] };
	orientation: Orientation;
};
	
export type IconWithTextProps = UIComponent & WithIcon & {
	colorTheme: ThemeColor;
	label: string;
};
	
export type ImageAndTextProps = UIComponent & {
	contentAlign: Alignment;
	contentMaxWidth: number;
	imageAlign: Alignment;
	imageFirst: boolean;
	imageMaxWidth: number;
	overlayAmount: number;
};
	
export type LabelWithTooltipProps = TextElement & WithIcon & {
	shortName: string;
	tooltip: string;
};
	
export type LinkProps = Renderable & WithIcon & {
	context: string;
	description: string;
	label: string;
	shortName: string;
	target: string;
	url: string;
};
	
export type LinkGroupProps = LayoutComponent & WithColorPair & WithNestedState & WithLayoutAlignment & WithContainerSize & {
	heading: string;
	layout: GroupLayout;
	maxPerRow: number;
};
	
export type ListItemProps = UIComponent;
	
export type ListComponentProps = UIComponent & {
	ordered: boolean;
};
	
export type MenuListItemProps = UIComponent & {
	isCurrentParent: boolean;
};
	
export type MenuListProps = UIComponent;
	
export type MenuProps = UIComponent & {
	colorTheme: ThemeColor;
};
	
export type PageHeaderProps = LayoutComponent & WithColorPair & WithLayoutAlignment & WithContainerSize & {
	breadcrumbs: array;
};
	
export type PageSectionProps = Renderable & WithContainerSize & {
	backgroundColor: ThemeColor|ThemeGradient;
	shortName: string;
};
	
export type PaginationProps = UIComponent & {
	colorTheme: ThemeColor;
};
	
export type ParagraphProps = TextElementExtended;
	
export type PostNavProps = UIComponent & {
	colorTheme: ThemeColor;
	entityName: string;
	links: { href: string; content: string };
	nextIconClass: string;
	prevIconClass: string;
};
	
export type PreprocessedHTMLProps = Renderable & {
	context: string;
	shortName: string;
};
	
export type PullquoteProps = TextElementExtended & WithColorPair & {
	citation: string;
};
	
export type ResponsivePanelProps = PanelComponent;
	
export type ResponsivePanelsProps = PanelGroupComponent & WithIcon & {
	breakpoint: string;
};
	
export type SeparatorProps = Renderable & WithContainerSize & WithNestedState & {
	colorTheme: ThemeColor;
	context: string;
	lineStyle: string;
	shortName: string;
};
	
export type SiteFooterProps = LayoutComponent & WithLayoutAlignment & WithContainerSize & {
	backgroundColor: ThemeColor|ThemeGradient;
	copyright: { siteName: string; startYear: number; endYear: number };
	devCredit: { authorName: string; authorUrl: string };
};
	
export type SiteHeaderProps = LayoutComponent & WithIcon & WithContainerSize & WithLayoutAlignment & {
	backgroundColor: ThemeColor|ThemeGradient;
	breakpoint: string;
	componentGroups: Record<string, Renderable>;
	logoUrl: string;
	overlayBackgroundColor: ThemeColor;
	responsiveStyle: string;
	submenuIcon: string;
};
	
export type TabPanelProps = PanelComponent;
	
export type TabsProps = PanelGroupComponent & WithContainerSize & WithNestedState;
	