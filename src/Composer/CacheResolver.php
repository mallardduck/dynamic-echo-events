<?php

namespace MallardDuck\DynamicEcho\Composer;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CacheResolver
{
    // TODO: consider Union Type once PHP8 support is a concern.
    private string $appNamespace;

    /**
     * The manifest path.
     *
     * @var string|null
     */
    public $manifestPath;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @var array|null
     */
    private ?array $manifest = null;

    public function __construct(string $appNamespace, string $manifestPath)
    {
        $this->appNamespace = $appNamespace;
        $this->manifestPath = $manifestPath;
    }

    public function setFilesystem(Filesystem $filesystem): self
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    public function build(): self
    {
        $packages = [];
        $events = [];
        // TODO: add a channels array to auto-discovery channels.
        if ($this->filesystem->exists($path = $this->getVendorPath() . '/composer/installed.json')) {
            $installed = json_decode($this->filesystem->get($path), true);
            $packages = $installed['packages'] ?? $installed;
        }

        $packages = collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['dynamic-echo'] ?? []];
        })->filter()->all();

        $namespaces = collect($packages)->mapWithKeys(static function ($val) {
            return [$val['namespace']];
        })->push($this->appNamespace)->all();

        if (null !== ($classMap = $this->getClassMap())) {
            $events = collect($classMap)->filter(static function ($val, $key) use ($namespaces) {
                return Str::startsWith($key, $namespaces);
            })->filter()->all();
        }

        $this->write($this->manifest = [
            'packages' => $packages,
            'events' => $events,
        ]);

        return $this;
    }

    public function getManifest(): array
    {
        if (! is_null($this->manifest)) {
            return $this->manifest;
        }

        if (! is_file($this->manifestPath)) {
            $this->build();
        }

        return $this->manifest = $this->filesystem->getRequire($this->manifestPath);
    }

    public function getEvents(): Collection
    {
        return collect($this->getManifest()['events']);
    }

    private function getVendorPath()
    {
        return app()->basePath() . '/vendor';
    }

    private function getClassMap(): ?array
    {
        if (
            $this->filesystem->exists(
                $path = $this->getVendorPath() . '/composer/autoload_classmap.php'
            )
        ) {
            return require $path;
        }
        return null;
    }

    protected function format($package)
    {
        return str_replace($this->getVendorPath() . '/', '', $package);
    }

    protected function write(array $manifest): void
    {
        if (! is_writable($dirname = dirname($this->manifestPath))) {
            throw new \RuntimeException("The {$dirname} directory must be present and writable.");
        }

        $this->filesystem->replace(
            $this->manifestPath,
            '<?php return ' . var_export($manifest, true) . ';'
        );
    }
}
