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
class FileGroup extends WrappedLayoutComponent {
    use ColorTheme;

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
        $this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'files';
        }

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

        $updatedInnerComponents = [];
        if ($attributes['heading']) {
            $this->heading = $attributes['heading'];
            array_push($updatedInnerComponents, new Heading([], $this->heading));
        }
        array_push($updatedInnerComponents, new Group(
            [
                'colorTheme' => $this->colorTheme->value,
                'shortName'  => 'file-group',
                'role'       => 'group'
            ],
            $fileComponents
        ));

        parent::__construct($attributes, $updatedInnerComponents, 'components.FileGroup.file-group');
    }
}
