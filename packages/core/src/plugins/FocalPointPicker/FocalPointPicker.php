<?php
namespace Doubleedesign\Comet\Core;

class FocalPointPicker {
    protected string $bladeFile = '';
    private string $html;

    public function __construct(string $html) {
        $this->bladeFile = 'plugins.FocalPointPicker.focal-point-picker';
        // TODO: Sanitise this. HTMLPurifier config in Utils currently strips too much
        $this->html = $html;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'content'    => $this->html,
        ])->render();
    }
}
