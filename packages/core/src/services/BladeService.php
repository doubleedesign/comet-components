<?php
namespace Doubleedesign\Comet\Core;
use Illuminate\{Events\Dispatcher, Filesystem\Filesystem};
use Illuminate\View\{Factory as ViewFactory, FileViewFinder};
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\{CompilerEngine, EngineResolver};
use InvalidArgumentException;
use RuntimeException;

class BladeService {
    private static ?ViewFactory $blade = null;
    private static ?BladeCompiler $compiler = null;
    private const CACHE_DIR = '/cache/blade';
    private const TEMPLATE_DIR = DIRECTORY_SEPARATOR;

    public static function getInstance(): ViewFactory {
        if (self::$blade === null) {
            self::initialize();
        }

        return self::$blade;
    }

    /**
     * Set up the Blade templating service by creating the compiler, resolver, and view finder,
     * and registering custom directives
     *
     * @return void
     */
    private static function initialize(): void {
        $filesystem = new Filesystem();
        self::$compiler = self::createCompiler($filesystem);

        $resolver = self::createEngineResolver();
        $viewFinder = self::createViewFinder($filesystem);

        self::$blade = new ViewFactory(
            $resolver,
            $viewFinder,
            new Dispatcher()
        );

        self::registerDirectives();
        self::stripFragmentsWhenCompiling();
    }

    /**
     * Create the Blade compiler
     *
     * @param  Filesystem  $filesystem
     *
     * @return BladeCompiler
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    private static function createCompiler(Filesystem $filesystem): BladeCompiler {
        $cachePath = self::setupCacheDirectory();

        return new BladeCompiler($filesystem, $cachePath);
    }

    /**
     * Create the cache directory for Blade templates
     *
     * @throws RuntimeException
     */
    private static function setupCacheDirectory(): string {
        $cachePath = dirname(__DIR__, 2) . self::CACHE_DIR;

        if (!is_dir($cachePath) && !mkdir($cachePath, 0755, true)) {
            throw new RuntimeException("Failed to create cache directory: $cachePath");
        }

        if (!is_writable($cachePath)) {
            throw new RuntimeException("Cache directory is not writable: $cachePath");
        }

        return $cachePath;
    }

    /**
     * Create the Blade engine resolver
     *
     * @return EngineResolver
     */
    private static function createEngineResolver(): EngineResolver {
        $resolver = new EngineResolver();
        $resolver->register('blade', function() {
            return new CompilerEngine(self::$compiler);
        });

        return $resolver;
    }

    /**
     * Create the Blade view finder
     *
     * @param  Filesystem  $filesystem
     *
     * @return FileViewFinder
     * @throws RuntimeException
     */
    private static function createViewFinder(Filesystem $filesystem): FileViewFinder {
        $templatePath = dirname(__DIR__, 1) . self::TEMPLATE_DIR;
        if (!is_dir($templatePath)) {
            throw new RuntimeException("Template directory not found: $templatePath");
        }

        // Allow for directory paths to be set in the config
        // e.g., setting /wp-content/themes/YOUR_THEME
        // would mean    /wp-content/themes/YOUR_THEME/components/Button/button.blade.php would override the button component template
        $componentPaths = Config::getInstance()->get('blade_component_paths');

        return new FileViewFinder($filesystem, [...$componentPaths, $templatePath]);
    }

    /**
     * Register custom Blade template directives
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private static function registerDirectives(): void {
        self::$compiler->directive('opentag', self::getOpenTagDirective());
        self::$compiler->directive('closetag', self::getCloseTagDirective());
        self::$compiler->directive('attributes', self::getAttributesDirective());
    }

    private static function getOpenTagDirective(): callable {
        return function($expression) {
            $expression = trim($expression, '()');

            return "<?php 
            echo '<' . htmlspecialchars($expression, ENT_QUOTES, 'UTF-8');
        ?>";
        };
    }

    private static function getCloseTagDirective(): callable {
        return function($expression) {
            $expression = trim($expression, '()');

            return "<?php 
            echo '</' . htmlspecialchars($expression, ENT_QUOTES, 'UTF-8') . '>';
        ?>";
        };
    }

    /**
     * Content of the custom attributes directive
     *
     * @return callable
     */
    private static function getAttributesDirective(): callable {
        return function($expression) {
            $expression = trim($expression, '()'); // Remove any parentheses Blade wraps around our expression

            return sprintf("<?php foreach(%s as \$key => \$value) { 
               if(!empty(\$value)) echo ' ' . \$key . '=\"' . \$value . '\"';
           } ?>", $expression);
        };
    }

    /**
     * <blade-fragment> tags are used purely for auto-formatting purposes in some Blade templates,
     * where we need a HTML tag so that the indentation is correct, but we don't want that tag to appear in the final output.
     * This function hooks into the compilation process to strip out those tags while preserving their content.
     *
     * @return void
     */
    private static function stripFragmentsWhenCompiling(): void {
        // Hook into the compilation process to strip blade-fragment tags
        self::$compiler->extend(function($value) {
            // Strip blade-fragment tags but preserve their content
            return preg_replace('/<blade-fragment[^>]*>(.*?)<\/blade-fragment>/s', '$1', $value);
        });
    }

}
