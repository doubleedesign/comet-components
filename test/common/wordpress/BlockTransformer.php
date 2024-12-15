<?php
namespace Doubleedesign\Comet\TestUtils\WordPress;
use RuntimeException;

class BlockTransformer {
    private string $node_path;
    private string $loader_path;
    private string $script_path;

    public function __construct() {
        $this->script_path =  './common/wordpress/get-block-innerHtml.mjs';
        $this->loader_path =  './common/wordpress/babel-loader.js';

        if (shell_exec('node -v') !== null) {
            $this->node_path = 'node';
        }
        // If node is not in the PATH, try to find the path to the executable
        else if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = [];
            exec('powershell -command "Get-Command node | Select-Object -ExpandProperty Source"', $output);
            if (!empty($output)) {
                $this->node_path = $output[0];
            }
        }
        else {
            $this->node_path = trim(shell_exec('which node'));
        }
    }

    /**
     * @throws RuntimeException
     */
    public function transform_block(string $name, array $attributes, array $content): string {
        if (empty($this->node_path)) {
            throw new RuntimeException('Node.js is not available');
        }

        $data = [
            'blockName'  => $name,
            'attributes' => $attributes,
            'content'    => $content
        ];

        $json_data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $base64_data = base64_encode($json_data);

        // Use Node script to get the inner HTML like it would be done in the WordPress editor
        $node_args = "--no-warnings --experimental-loader $this->loader_path"; // --no-warnings is to suppress warnings about experimental loader
        $command = sprintf(
            '%s %s "%s" %s 2>&1',
            $this->node_path,
            $node_args,
            $this->script_path,
            escapeshellarg($base64_data)
        );

        $result = shell_exec($command);

        if(str_contains($result, 'triggerUncaughtException')) {
            throw new RuntimeException("BlockTransformer: A Node error occurred while transforming the block.\n $result\n");
        }

        $updated_block = [
            'blockName'    => $name,
            'attrs'        => $attributes,
            'innerHTML'    => $result,
            'innerContent' => [$result],
            'innerBlocks'  => []
        ];

        $serialized_block = serialize_block($updated_block);

        return trim(do_blocks($serialized_block));
    }
}
