<?php
// Generated by ./scripts/generate-tag-enum.ts
namespace Doubleedesign\Comet\Components;


enum Tag: string implements ITag {
	case A = 'a';
	case ABBR = 'abbr';
	case ADDRESS = 'address';
	case ARTICLE = 'article';
	case ASIDE = 'aside';
	case AUDIO = 'audio';
	case BDI = 'bdi';
	case BDO = 'bdo';
	case BLOCKQUOTE = 'blockquote';
	case BR = 'br';
	case BUTTON = 'button';
	case CANVAS = 'canvas';
	case CAPTION = 'caption';
	case CITE = 'cite';
	case CODE = 'code';
	case COL = 'col';
	case COLGROUP = 'colgroup';
	case DATA = 'data';
	case DATALIST = 'datalist';
	case DD = 'dd';
	case DEL = 'del';
	case DETAILS = 'details';
	case DFN = 'dfn';
	case DIALOG = 'dialog';
	case DIV = 'div';
	case DL = 'dl';
	case DT = 'dt';
	case EM = 'em';
	case EMBED = 'embed';
	case FIELDSET = 'fieldset';
	case FIGCAPTION = 'figcaption';
	case FIGURE = 'figure';
	case FOOTER = 'footer';
	case FORM = 'form';
	case H1 = 'h1';
	case H2 = 'h2';
	case H3 = 'h3';
	case H4 = 'h4';
	case H5 = 'h5';
	case H6 = 'h6';
	case HEAD = 'head';
	case HEADER = 'header';
	case HGROUP = 'hgroup';
	case HR = 'hr';
	case I = 'i';
	case IFRAME = 'iframe';
	case IMG = 'img';
	case INPUT = 'input';
	case INS = 'ins';
	case KBD = 'kbd';
	case LABEL = 'label';
	case LEGEND = 'legend';
	case LI = 'li';
	case MAIN = 'main';
	case MAP = 'map';
	case MARK = 'mark';
	case META = 'meta';
	case METER = 'meter';
	case NAV = 'nav';
	case NOSCRIPT = 'noscript';
	case OBJECT = 'object';
	case OL = 'ol';
	case OPTGROUP = 'optgroup';
	case OPTION = 'option';
	case OUTPUT = 'output';
	case P = 'p';
	case PICTURE = 'picture';
	case PRE = 'pre';
	case PROGRESS = 'progress';
	case Q = 'q';
	case S = 's';
	case SAMP = 'samp';
	case SEARCH = 'search';
	case SECTION = 'section';
	case SELECT = 'select';
	case SMALL = 'small';
	case SOURCE = 'source';
	case SPAN = 'span';
	case STRONG = 'strong';
	case STYLE = 'style';
	case SUB = 'sub';
	case SUMMARY = 'summary';
	case SUP = 'sup';
	case TABLE = 'table';
	case TBODY = 'tbody';
	case TD = 'td';
	case TEXTAREA = 'textarea';
	case TFOOT = 'tfoot';
	case TH = 'th';
	case THEAD = 'thead';
	case TIME = 'time';
	case TITLE = 'title';
	case TR = 'tr';
	case TRACK = 'track';
	case U = 'u';
	case UL = 'ul';
	case VAR = 'var';
	case VIDEO = 'video';
	case WBR = 'wbr';

	public const array GLOBAL_ATTRIBUTES = [
		'accesskey',
        'aria-label',
        'aria-labelledby',
        'aria-describedby',
        'aria-controls',
		'autocapitalize',
		'autofocus',
		'class',
		'contenteditable',
		'data-*',
		'dir',
		'draggable',
		'enterkeyhint',
		'exportparts',
		'hidden',
		'id',
		'inert',
		'inputmode',
		'is',
		'itemid',
		'itemprop',
		'itemref',
		'itemscope',
		'itemtype',
		'lang',
		'nonce',
		'part',
		'popover',
		'slot',
		'spellcheck',
		'style',
		'tabindex',
		'title',
		'translate',
		'writingsuggestions',
	];

	public function get_valid_attributes(): array {
		$local = match ($this) {
			self::A => ['download', 'href', 'hreflang', 'ping', 'referrerpolicy', 'rel', 'target', 'type'],
			self::AUDIO => [
				'autoplay',
				'controls',
				'controlslist',
				'crossorigin',
				'disableremoteplayback',
				'loop',
				'muted',
				'preload',
				'src',
			],
			self::BLOCKQUOTE, self::Q => ['cite'],
			self::BUTTON => [
                'aria-expanded',
                'aria-haspopup',
                'aria-pressed',
				'disabled',
				'form',
				'formaction',
				'formenctype',
				'formmethod',
				'formnovalidate',
				'formtarget',
				'name',
				'popovertarget',
				'popovertargetaction',
				'type',
				'value',
			],
			self::CANVAS => ['height', 'width'],
			self::COL => ['span'],
			self::COLGROUP => ['span'],
			self::DATA => ['value'],
			self::DEL => ['cite', 'datetime'],
			self::DETAILS => ['open', 'name'],
			self::DIALOG => ['open', 'aria-modal'],
			self::EMBED => ['height', 'src', 'type', 'width'],
			self::FIELDSET => ['disabled', 'form', 'name'],
			self::FORM => ['accept-charset', 'autocomplete', 'name', 'rel'],
			self::IFRAME => [
				'allow',
				'allowfullscreen',
				'height',
				'loading',
				'name',
				'referrerpolicy',
				'sandbox',
				'src',
				'srcdoc',
				'width',
			],
			self::IMG => [
				'alt',
				'crossorigin',
				'decoding',
				'elementtiming',
				'fetchpriority',
				'height',
				'ismap',
				'loading',
				'referrerpolicy',
				'sizes',
				'src',
				'srcset',
				'width',
				'usemap',
			],
			self::INPUT => [
				'accept',
				'alt',
                'aria-invalid',
                'aria-required',
				'autocomplete',
				'capture',
				'checked',
				'dirname',
				'disabled',
				'form',
				'formaction',
				'formenctype',
				'formmethod',
				'formnovalidate',
				'formtarget',
				'height',
				'list',
				'max',
				'maxlength',
				'min',
				'minlength',
				'multiple',
				'name',
				'pattern',
				'placeholder',
				'popovertarget',
				'popovertargetaction',
				'readonly',
				'required',
				'size',
				'src',
				'step',
				'type',
				'value',
				'width',
			],
			self::INS => ['cite', 'datetime'],
			self::LABEL => ['for'],
			self::LI => ['value'],
			self::MAP => ['name'],
			self::META => ['charset', 'content', 'http-equiv', 'name'],
			self::METER => ['aria-valuemin', 'aria-valuemax', 'aria-valuenow', 'value', 'min', 'max', 'low', 'high', 'optimum', 'form'],
			self::OBJECT => ['data', 'form', 'height', 'name', 'type', 'width'],
			self::OL => ['reversed', 'start', 'type'],
			self::OPTGROUP => ['disabled', 'label'],
			self::OPTION => ['disabled', 'label', 'selected', 'value'],
			self::OUTPUT => ['for', 'form', 'name'],
			self::PROGRESS => ['aria-valuemin', 'aria-valuemax', 'aria-valuenow', 'max', 'value'],
			self::SELECT => ['autocomplete', 'disabled', 'form', 'multiple', 'name', 'required', 'size'],
			self::SOURCE => ['type', 'src', 'srcset', 'sizes', 'media', 'height', 'width'],
			self::STYLE => ['media'],
            self::TABLE => ['aria-colcount', 'aria-rowcount'],
			self::TD => ['colspan', 'headers', 'rowspan'],
			self::TEXTAREA => [
                'aria-invalid',
                'aria-required',
				'autocomplete',
				'autocorrect',
				'cols',
				'dirname',
				'disabled',
				'form',
				'maxlength',
				'minlength',
				'name',
				'placeholder',
				'readonly',
				'required',
				'rows',
				'wrap',
			],
			self::TH => ['abbr', 'colspan', 'headers', 'rowspan', 'scope'],
			self::TIME => ['datetime'],
			self::TRACK => ['default', 'kind', 'label', 'src', 'srclang'],
			self::VIDEO => [
				'autoplay',
				'controls',
				'controlslist',
				'crossorigin',
				'disablepictureinpicture',
				'disableremoteplayback',
				'height',
				'loop',
				'muted',
				'playsinline',
				'poster',
				'preload',
				'src',
				'width',
			],
			default => [],
		};

		return array_merge(self::GLOBAL_ATTRIBUTES, $local);
	}
}
