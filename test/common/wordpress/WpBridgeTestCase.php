<?php
namespace Doubleedesign\Comet\TestUtils\WordPress;
use Doubleedesign\Comet\TestUtils\CometTestCase;

abstract class WpBridgeTestCase extends CometTestCase implements IWpBridgeTest {
    private static WpBridgeTestSetup $setupInstance;
    protected static BlockTransformer $transformer;

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();
        self::$setupInstance = new WpBridgeTestSetup();
        self::$transformer = new BlockTransformer();
        self::$setupInstance->setUpBeforeClass();
    }

    protected function setUp(): void {
        parent::setUp();
        self::$setupInstance->setUp();
    }

    /**
     * Register the required blocks for this test case/suite
     * @param array $blockNames
     *
     * @return void
     */
    public function register_blocks(array $blockNames): void {
        $includesPath = self::$setupInstance->get_wp_includes_path();

        foreach ($blockNames as $blockName) {
            if(in_array($blockName, ['paragraph'])) {
                register_block_type_from_metadata("$includesPath/blocks/$blockName/block.json");
            }
            else {
                require_once("$includesPath/blocks/$blockName.php");
            }
        }
    }
}
