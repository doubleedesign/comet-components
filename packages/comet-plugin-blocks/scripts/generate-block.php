<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Doubleedesign\Comet\Core\Utils;

class CometWpBlockGenerator {
    private string $block_name;
    private string $block_folder;

    public function __construct($block_name) {
        $this->block_name = $block_name;
        $this->block_folder = __DIR__ . "/../src/blocks/$this->block_name";
        if (!file_exists($this->block_folder)) {
            mkdir($this->block_folder);
        }
    }

    public function run(): void {
        $this->copy_and_update_template('block.json');
        $this->copy_and_update_template('fields.php');
        $this->copy_and_update_template('render.php');

        if (!file_exists("$this->block_folder/block.json") || !file_exists("$this->block_folder/fields.php") || !file_exists("$this->block_folder/render.php")) {
            $this->log("Error: Failed to create block files in $this->block_folder", 'error');
			exit(1);
        }

        $this->log("Block '$this->block_name' created successfully at $this->block_folder", 'success');
    }

    private function copy_and_update_template(string $template_file): void {
        $template_path = __DIR__ . '/' . $template_file;
        $template_content = file_get_contents($template_path);
        $template_content = str_replace('block-template', Utils::kebab_case($this->block_name), $template_content);
        $template_content = str_replace('Block Template', Utils::title_case($this->block_name), $template_content);
        $template_content = str_replace('BlockTemplate', Utils::pascal_case($this->block_name), $template_content);

        file_put_contents("$this->block_folder/$template_file", $template_content);
    }

    private static function log(string $message, string $type): void {
        // ANSI colour codes
        $red = "\033[0;31m";
        $green = "\033[0;32m";
        $yellow = "\033[0;33m";
        $cyan = "\033[0;36m";
        $white = "\033[0;37m";
        $reset = "\033[0m";

        $color = match ($type) {
            'info'    => $cyan,
            'success' => $green,
            'error'   => $red,
            'warning' => $yellow,
            default   => $white,
        };

        echo $color . $message . $reset . "\n";

        if ($type === 'error') {
            \Symfony\Component\VarDumper\VarDumper::dump([
                'message'   => $message,
                'backtrace' => debug_backtrace()
            ]);
        }
    }
}

// Get block name from command line argument and make sure it's kebab-cased
if (count($argv) < 2) {
    echo "Usage: php generate-block.php <block-name>\n";
    exit(1);
}
$block_name = $argv[1];
$block_name = Utils::kebab_case($block_name);

// If the folder already exists, exit with error
$block_folder = __DIR__ . "/../src/blocks/$block_name";
if (file_exists($block_folder)) {
    throw new Exception("Error: Block folder already exists at $block_folder\n");
}

$generator = new CometWpBlockGenerator($block_name);
$generator->run();
