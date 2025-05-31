<?php
use Doubleedesign\Comet\Core\Assets;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';
require_once __DIR__ . '/../../vendor/antecedent/patchwork/Patchwork.php';

beforeEach(function() {
    $this->assetsMock = Mockery::mock('alias:' . Assets::class);
    $this->assetsMock->shouldReceive('get_instance')->andReturnSelf();
    $this->assetsMock->shouldReceive('add_stylesheet')->andReturnSelf();
    $this->assetsMock->shouldReceive('add_script')->andReturnSelf();
});

afterEach(function() {
    Mockery::close();
});
