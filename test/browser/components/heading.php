<div id="browser-test-content">
    <?php
    use Doubleedesign\Comet\Core\Heading;

    try {
        $content = $_REQUEST['content'] ?? 'Heading';
        if (isset($_SERVER['argv'])) {
            parse_str(array_reverse($_SERVER['argv'])[0], $args);
            $attributes = array_merge(
                array_filter(array('className' => $_REQUEST['style'] ? 'is-style-' . $_REQUEST['style'] : ''), function ($key, $value) {
                    return !empty($value);
                }, ARRAY_FILTER_USE_BOTH),
                array_filter($args, function ($key) {
                    return $key !== 'style';
                }, ARRAY_FILTER_USE_KEY)
            );
        }
        else if (isset($_REQUEST['style'])) {
            $attributes = array('className' => $_REQUEST['style'] ? 'is-style-' . $_REQUEST['style'] : '');
        }
        else {
            $attributes = array();
        }

        $component = new Heading($attributes, $content);
        $component->render();
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>
</div>
