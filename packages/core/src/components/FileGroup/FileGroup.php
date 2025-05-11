<?php
namespace Doubleedesign\Comet\Core;

/**
 * FileGroup component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a list of downloadable file links with details about them.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class FileGroup extends UIComponent {
    use ColorTheme;

    /**
     * @param  array  $attributes
     * @param array<File|array<string,string> $files - Either an array of File objects or an array of associative arrays corresponding to File fields
     */
    public function __construct(array $attributes, array $files) {
        $this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
        $innerComponents = array_map(function($file) {
            if ($file instanceof File) {
                return $file;
            }

            return new File([
                'context'     => 'file-group',
                'url'         => $file['url'],
                'title'       => $file['title'],
                'description' => $file['description'],
                'size'        => $file['size'] ?? '',
                'mimeType'    => $file['mimeType'],
                'uploadDate'  => $file['date'] ?? '',
                'colorTheme'  => $file['colorTheme'] ?? null // selectively enables per-file color theme styling
            ]);
        }, $files);

        parent::__construct($attributes, $innerComponents, 'components.FileGroup.file-group');
    }

    public function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            ['data-color-theme' => $this->colorTheme->value]
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
