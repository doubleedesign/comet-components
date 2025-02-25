<?php
namespace Doubleedesign\Comet\Core;

class File extends Renderable {
	use ColorTheme;

	protected string $url;
	protected ?string $title;
	protected ?string $description;
	protected ?string $size;
	/**
	 * @var string|mixed|null $mimeType
	 * @description MIME type of the file
	 */
	protected ?string $mimeType;
	protected ?string $uploadDate;
	/**
	 * @var string $iconPrefix
	 * @description Icon prefix class name.
	 */
	protected string $iconPrefix = 'fa-solid';
	/**
	 * @var ?string $icon
	 * @description Icon class name. If not set, defaults to one matching the file type.
	 */
	protected ?string $icon;


	function __construct(array $attributes) {
		$this->set_color_theme_from_attrs($attributes);
		$this->url = $attributes['url'] ?? '';
		$this->title = $attributes['title'] ?? 'Untitled file';
		$this->description = $attributes['description'] ?? null;
		$this->size = $attributes['size'] ?? null;
		$this->mimeType = $attributes['mimeType'] ?? null;
		$this->uploadDate = $attributes['uploadDate'] ?? null;
		$this->iconPrefix = $attributes['iconPrefix'] ?? $this->iconPrefix;

		if (isset($attributes['icon'])) {
			$this->icon = $attributes['icon']; // TODO: Sanitisation/validation of icon class name
		}
		else {
			$this->icon = match ($this->mimeType) {
				'application/pdf' => 'fa-file-pdf',
				'text/plain' => 'fa-file-alt',
				'text/calendar' => 'fa-calendar-alt',
				'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-lines',
				'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-file-spreadsheet',
				'application/zip', 'application/x-gzip', 'application/x-tar', 'application/x-7z-compressed' => 'fa-file-zipper',
				default => 'fa-file',
			};
		}

		parent::__construct($attributes, 'components.FileGroup.File.file');
	}

	function get_html_attributes(): array {
		$attributes = array_merge(
			parent::get_html_attributes(),
			['href' => $this->url]
		);

		if ($this->colorTheme) {
			$attributes['data-color-theme'] = $this->colorTheme->value;
		}

		return $attributes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'     => implode(' ', $this->get_filtered_classes()),
			'attributes'  => $this->get_html_attributes(),
			'url'         => $this->url,
			'iconPrefix'  => $this->iconPrefix,
			'icon'        => $this->icon,
			'title'       => $this->title,
			'description' => $this->description,
			'size'        => $this->size,
			'mimeType'    => $this->mimeType,
			'uploadDate'  => $this->uploadDate,
			'bem_prefix'  => $this->get_bem_name()
		])->render();
	}
}
