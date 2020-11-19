<?php

namespace MallardDuck\DynamicEcho\Loader;

use Illuminate\Filesystem\Filesystem;

// TODO: Build to: a) find packages that register for discovery, b) load their events and channels, and c) cache it.
class ComposerResolver
{
    // TODO: consider Union Type once PHP8 support is a concern.
    private string $appNamespace;

    /**
     * @var array
     */
    private array $manifest;

    /**
     * The manifest path.
     *
     * @var string|null
     */
    public $manifestPath;

    public function __construct(Filesystem $filesystem, string $appNamespace)
    {
        $this->filesystem = $filesystem;
        $this->appNamespace = $appNamespace;

        // Determine the path of the cached manifest
        $this->manifestPath = app()->bootstrapPath('cache/dynamic-echo-packages.php');
    }

    private function buildEvents()
    {
        $this->appEvents = collect(require(app()->basePath() . '/vendor/composer/autoload_classmap.php'))
            ->filter(static function ($val, $key) use ($appNamespace) {
                return str_starts_with($key, $appNamespace);
            });
    }

    protected function getManifest()
    {
        if (! is_null($this->manifest)) {
            return $this->manifest;
        }

        if (! is_file($this->manifestPath)) {
            $this->build();
        }

        return $this->manifest = is_file($this->manifestPath) ?
            $this->filesystem->getRequire($this->manifestPath) : [];
    }

    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);

            $packages = $installed['packages'] ?? $installed;
        }

        $this->write(collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
        })->each(function ($configuration) use (&$ignore) {
            $ignore = array_merge($ignore, $configuration['dont-discover'] ?? []);
        })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
            return $ignoreAll || in_array($package, $ignore);
        })->filter()->all());
    }

    protected function write(array $manifest)
    {
        if (! is_writable($dirname = dirname($this->manifestPath))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        $this->files->replace(
            $this->manifestPath, '<?php return '.var_export($manifest, true).';'
        );
    }
}
