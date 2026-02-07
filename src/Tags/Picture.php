<?php

namespace Studio1902\PeakTools\Tags;

use Statamic\Assets\Asset;
use Statamic\Facades\Asset as AssetFacade;
use Statamic\Facades\Image;
use Statamic\Support\Str;
use Statamic\Tags\Tags;
use Statamic\View\Antlers\Language\Runtime\StackReplacementManager;

class Picture extends Tags
{
    /**
     * Maps to {{ picture }}.
     */
    public function index(): string
    {
        $image = $this->params->get(['image', 'src', 'id', 'path']);

        return $this->generate($image);
    }

    /**
     * Maps to {{ picture:field_name }}.
     */
    public function wildcard(string $tag): string
    {
        $image = $this->context->value($tag);

        return $this->generate($image);
    }

    protected function generate(mixed $image): string
    {
        if (! $image) {
            return '';
        }

        $asset = $this->resolveAsset($image);

        if (! $asset) {
            return '';
        }

        $extension = strtolower($asset->extension());

        // SVG and GIF passthrough - no processing
        if (in_array($extension, ['svg', 'gif'])) {
            return $this->buildSimpleImgTag($asset);
        }

        // Get formats configuration
        $formats = $this->getFormats();

        // Get aspect ratio
        $ratio = $this->parseAspectRatio($this->params->get('aspect_ratio'));

        // Build srcset for all widths
        $srcsets = $this->buildSrcsets($asset, $ratio);

        // Handle art direction sources
        $artDirectionSources = $this->buildArtDirectionSources();

        // Build the picture HTML
        $html = $this->buildPictureHtml($asset, $srcsets, $formats, $artDirectionSources);

        // Handle preload if requested
        if ($this->params->bool('preload', false)) {
            $this->injectPreloadLink($asset, $srcsets, $formats);
        }

        return $html;
    }

    protected function resolveAsset(mixed $image): ?Asset
    {
        if ($image instanceof Asset) {
            return $image;
        }

        if (is_array($image)) {
            $image = $image[0] ?? null;
            if ($image instanceof Asset) {
                return $image;
            }
        }

        if (is_string($image)) {
            // Asset ID format (container::path)
            if (Str::contains($image, '::')) {
                return AssetFacade::find($image);
            }

            // Try to find as path
            return AssetFacade::find($image);
        }

        return null;
    }

    protected function parseAspectRatio(?string $value): ?float
    {
        if (! $value) {
            return null;
        }

        $value = trim($value);

        if (str_contains($value, '/')) {
            [$num, $denom] = explode('/', $value);

            return (float) $denom / (float) $num;
        }

        return null;
    }

    protected function buildSrcsets(Asset $asset, ?float $ratio): array
    {
        $originalRatio = $asset->height() && $asset->width()
            ? $asset->height() / $asset->width()
            : null;

        $useRatio = $ratio ?? $originalRatio;
        $srcsets = [];

        foreach ($this->getWidths() as $width) {
            $height = $useRatio ? (int) round($width * $useRatio) : null;

            $srcsets[] = [
                'width' => $width,
                'height' => $height,
                'ratio' => $useRatio,
            ];
        }

        return $srcsets;
    }

    protected function generateGlideUrl(Asset $asset, int $width, ?int $height, string $format): string
    {
        /** @var \Statamic\Imaging\GlideImageManipulator $manipulator */
        $manipulator = Image::manipulate($asset);
        $manipulator
            ->width($width)
            ->format($format)
            ->quality($this->params->int('quality', 85));

        if ($height) {
            $manipulator->height($height)->fit('crop_focal');
        }

        // Apply optional params
        if ($bg = $this->params->get('bg')) {
            $manipulator->bg($bg);
        }
        if ($blur = $this->params->get('blur')) {
            $manipulator->blur($blur);
        }
        if ($brightness = $this->params->get('brightness')) {
            $manipulator->brightness($brightness);
        }
        if ($contrast = $this->params->get('contrast')) {
            $manipulator->contrast($contrast);
        }
        if ($filter = $this->params->get('filter')) {
            $manipulator->filter($filter);
        }
        if ($flip = $this->params->get('flip')) {
            $manipulator->flip($flip);
        }
        if ($gamma = $this->params->get('gamma')) {
            $manipulator->gamma($gamma);
        }
        if ($orient = $this->params->get('orient')) {
            $manipulator->orient($orient);
        }
        if ($pixelate = $this->params->get('pixelate')) {
            $manipulator->pixelate($pixelate);
        }
        if ($sharpen = $this->params->get('sharpen')) {
            $manipulator->sharpen($sharpen);
        }

        return $manipulator->build();
    }

    protected function buildPictureHtml(Asset $asset, array $srcsets, array $formats, array $artDirectionSources = []): string
    {
        $html = "<!-- picture tag -->\n<picture>\n";

        // Add art direction sources first (if any)
        foreach ($artDirectionSources as $source) {
            $html .= $source;
        }

        // Build source elements for each format
        foreach ($formats as $format => $mimeType) {
            $srcsetParts = [];
            foreach ($srcsets as $srcset) {
                $url = $this->generateGlideUrl($asset, $srcset['width'], $srcset['height'], $format);
                $srcsetParts[] = "{$url} {$srcset['width']}w";
            }

            $sizes = (string) $this->params->get('sizes', '(min-width: 1280px) 640px, (min-width: 768px) 50vw, 90vw');

            $html .= sprintf(
                "    <source\n        srcset=\"%s\"\n        sizes=\"%s\"\n        type=\"%s\"\n    >\n",
                implode(",\n            ", $srcsetParts),
                e($sizes),
                $mimeType
            );
        }

        // Build img element
        $html .= $this->buildImgTag($asset);

        $html .= "</picture>\n<!-- End: picture tag -->";

        return $html;
    }

    protected function buildImgTag(Asset $asset): string
    {
        $originalRatio = $asset->height() && $asset->width()
            ? $asset->height() / $asset->width()
            : 1;

        // Fallback src at 1024px (uses original format like Antlers version)
        $fallbackUrl = $this->generateGlideUrl(
            $asset,
            1024,
            (int) round(1024 * $originalRatio),
            $asset->extension()
        );

        $cover = $this->params->bool('cover', false);
        $contain = $this->params->bool('contain', false);
        $class = (string) $this->params->get('class', '');
        $lazy = $this->params->bool('lazy', true);
        $alt = $asset->get('alt');
        $focus = $asset->get('focus');

        $classAttr = match (true) {
            $cover => 'object-cover w-full h-full object-(--focal-point)'.($class ? ' '.e($class) : ''),
            $contain => 'object-contain w-full h-full object-(--focal-point)'.($class ? ' '.e($class) : ''),
            default => e($class),
        };

        $styleAttr = '';
        if (($cover || $contain) && is_string($focus)) {
            $styleAttr = sprintf(' style="--focal-point: %s"', $this->focusToPosition($focus));
        }

        $altAttr = is_string($alt) ? e($this->ensureEndsWithPeriod($alt)) : '';
        $ariaHidden = $alt ? '' : ' aria-hidden="true"';
        $loading = $lazy ? ' loading="lazy"' : '';

        return sprintf(
            "    <img\n        width=\"%d\"\n        height=\"%d\"\n        src=\"%s\"\n        alt=\"%s\"\n        class=\"%s\"%s%s%s\n    >\n",
            $asset->width(),
            $asset->height(),
            $fallbackUrl,
            $altAttr,
            $classAttr,
            $styleAttr,
            $ariaHidden,
            $loading
        );
    }

    protected function buildSimpleImgTag(Asset $asset): string
    {
        $cover = $this->params->bool('cover', false);
        $contain = $this->params->bool('contain', false);
        $class = (string) $this->params->get('class', '');
        $lazy = $this->params->bool('lazy', true);
        $alt = $asset->get('alt');
        $focus = $asset->get('focus');

        $classAttr = match (true) {
            $cover => 'object-cover w-full h-full object-(--focal-point)'.($class ? ' '.e($class) : ''),
            $contain => 'object-contain w-full h-full object-(--focal-point)'.($class ? ' '.e($class) : ''),
            default => e($class),
        };

        $styleAttr = '';
        if (($cover || $contain) && is_string($focus)) {
            $styleAttr = sprintf(' style="--focal-point: %s"', $this->focusToPosition($focus));
        }

        $altAttr = is_string($alt) ? e($this->ensureEndsWithPeriod($alt)) : '';
        $ariaHidden = $alt ? '' : ' aria-hidden="true"';
        $loading = $lazy ? ' loading="lazy"' : '';

        return sprintf(
            "<!-- picture tag -->\n<picture>\n    <img\n        class=\"%s\"%s\n        src=\"%s\"\n        alt=\"%s\"\n        width=\"%d\"\n        height=\"%d\"%s%s\n    />\n</picture>\n<!-- End: picture tag -->",
            $classAttr,
            $styleAttr,
            $asset->url(),
            $altAttr,
            $asset->width(),
            $asset->height(),
            $ariaHidden,
            $loading
        );
    }

    protected function buildArtDirectionSources(): array
    {
        $sources = $this->params->get('sources');

        if (! $sources || ! is_array($sources)) {
            return [];
        }

        $formats = $this->getFormats();
        $result = [];

        foreach ($sources as $source) {
            $sourceImage = $source['image'] ?? null;
            $media = $source['media'] ?? null;

            if (! $sourceImage || ! $media) {
                continue;
            }

            $sourceAsset = $this->resolveAsset($sourceImage);
            if (! $sourceAsset) {
                continue;
            }

            $aspectRatio = $source['aspect_ratio'] ?? $this->params->get('aspect_ratio');
            $ratio = $this->parseAspectRatio(is_string($aspectRatio) ? $aspectRatio : null);
            $srcsets = $this->buildSrcsets($sourceAsset, $ratio);

            foreach ($formats as $format => $mimeType) {
                $srcsetParts = [];
                foreach ($srcsets as $srcset) {
                    $url = $this->generateGlideUrl($sourceAsset, $srcset['width'], $srcset['height'], $format);
                    $srcsetParts[] = "{$url} {$srcset['width']}w";
                }

                $sizes = (string) ($source['sizes'] ?? $this->params->get('sizes', '100vw'));

                $result[] = sprintf(
                    "    <source\n        media=\"%s\"\n        srcset=\"%s\"\n        sizes=\"%s\"\n        type=\"%s\"\n    >\n",
                    e($media),
                    implode(",\n            ", $srcsetParts),
                    e($sizes),
                    $mimeType
                );
            }
        }

        return $result;
    }

    protected function injectPreloadLink(Asset $asset, array $srcsets, array $formats): void
    {
        // Use the first (preferred) format for preloading
        $preferredFormat = array_key_first($formats);
        $mimeType = $formats[$preferredFormat];

        $srcsetParts = [];
        foreach ($srcsets as $srcset) {
            $url = $this->generateGlideUrl($asset, $srcset['width'], $srcset['height'], $preferredFormat);
            $srcsetParts[] = "{$url} {$srcset['width']}w";
        }

        $sizes = (string) $this->params->get('sizes', '100vw');

        $link = sprintf(
            '<link rel="preload" as="image" imagesrcset="%s" imagesizes="%s" type="%s" fetchpriority="high">',
            e(implode(', ', $srcsetParts)),
            e($sizes),
            $mimeType
        );

        StackReplacementManager::pushStack('head', $link);
    }

    protected function getFormats(): array
    {
        $formats = $this->params->get('formats');

        if ($formats && is_array($formats)) {
            return $formats;
        }

        return config('statamic-peak-tools.picture.default_formats', [
            'avif' => 'image/avif',
            'webp' => 'image/webp',
            'jpg' => 'image/jpeg',
        ]);
    }

    protected function getWidths(): array
    {
        $widths = $this->params->get('widths');

        if ($widths && is_array($widths)) {
            return $widths;
        }

        return config('statamic-peak-tools.picture.default_widths', [
            320, 480, 640, 768, 1024, 1280, 1440, 1536, 1680,
        ]);
    }

    protected function focusToPosition(string $focus): string
    {
        if (! str_contains($focus, '-')) {
            return $focus;
        }

        return vsprintf('%d%% %d%%', explode('-', $focus));
    }

    protected function ensureEndsWithPeriod(string $text): string
    {
        $text = trim($text);
        if ($text && ! preg_match('/[.!?]$/', $text)) {
            $text .= '.';
        }

        return $text;
    }
}
