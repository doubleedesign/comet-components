<?php
namespace Doubleedesign\Comet\Core;

/**
 * FileGroup component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a list of downloadable file links with details about them.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class FileGroup extends LayoutComponent {
    use ColorTheme;
    use NestedState;

    /**
     * @var ?string $heading
     * @description Optional heading for the section.
     */
    protected ?string $heading;

    /**
     * @param  array  $attributes
     * @param array<File|array<string,string> $files - Either an array of File objects or an array of associative arrays corresponding to File fields
     */
    public function __construct(array $attributes, array $files) {
        $this->set_color_theme($attributes);
        $this->set_is_nested($attributes['isNested'] ?? null);
        $this->heading = $attributes['heading'] ?? null;

        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'files';
        }

        $groupAttrs = [
            'shortName'  => 'file-group',
            'role'       => 'group',
        ];

        $fileComponents = array_map(function($file) {
            if ($file instanceof File) {
                return $file;
            }

            return new File([
                'url'         => $file['url'],
                'title'       => $file['title'],
                'description' => $file['description'],
                'size'        => $file['size'] ?? '',
                'mimeType'    => $file['mimeType'],
                'uploadDate'  => $file['date'] ?? '',
                'colorTheme'  => $file['colorTheme'] ?? null // selectively enables per-file color theme styling
            ]);
        }, $files);
        $innerContent = new Group($groupAttrs, $fileComponents);

        $updatedInnerComponents = $this->heading ? [new Heading([], $this->heading), $innerContent] : [$innerContent];

        parent::__construct($attributes, $updatedInnerComponents, 'components.FileGroup.file-group');
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }
}
