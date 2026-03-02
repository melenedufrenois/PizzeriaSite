<?php

namespace App\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Provides the responsive_image() Twig function.
 *
 * Generates a <picture> element with:
 *  - AVIF and WebP <source> fallbacks
 *  - srcset / sizes for multiple resolutions
 *  - native lazy-loading (loading="lazy") for non-critical images
 *  - LQIP blur-up placeholder (style + data-controller)
 *  - reduced output when the Save-Data request header is present
 */
class ResponsiveImageExtension extends AbstractExtension
{
    /** Default breakpoint widths (pixels) generated in srcset. */
    private const DEFAULT_WIDTHS = [320, 640, 960, 1280];

    /** Default sizes hint. */
    private const DEFAULT_SIZES = '(max-width: 640px) 100vw, (max-width: 1024px) 75vw, 50vw';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'responsive_image',
                [$this, 'renderResponsiveImage'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Render a responsive <picture> element.
     *
     * @param string $src     Path/URL of the original (full-size) image.
     * @param string $alt     Alt text.
     * @param array  $options {
     *   widths?:      int[]   Pixel widths to include in srcset.
     *   sizes?:       string  HTML sizes attribute value.
     *   lazy?:        bool    Use loading="lazy" (default: true).
     *   critical?:    bool    Mark as critical (eager + high fetchpriority, default: false).
     *   class?:       string  CSS class(es) for the <img>.
     *   width?:       int     Intrinsic width — prevents CLS.
     *   height?:      int     Intrinsic height — prevents CLS.
     *   placeholder?: string  Data URI or URL for the LQIP placeholder.
     * }
     */
    public function renderResponsiveImage(string $src, string $alt, array $options = []): string
    {
        $widths = $options['widths'] ?? self::DEFAULT_WIDTHS;
        $sizes = $options['sizes'] ?? self::DEFAULT_SIZES;
        $lazy = $options['lazy'] ?? true;
        $critical = $options['critical'] ?? false;
        $class = $options['class'] ?? '';
        $width = isset($options['width']) ? (int) $options['width'] : null;
        $height = isset($options['height']) ? (int) $options['height'] : null;
        $placeholder = $options['placeholder'] ?? null;

        // Save-Data: serve only the smallest variant for non-critical images.
        if ($this->isSaveData() && !$critical) {
            $widths = [min($widths)];
        }

        $loading = ($lazy && !$critical) ? 'lazy' : 'eager';
        $fetchPriority = $critical ? 'high' : 'auto';
        $decoding = $critical ? 'sync' : 'async';

        $sizes = htmlspecialchars($sizes, ENT_QUOTES);

        // Build <source> elements for modern formats.
        $avifSrcset = $this->generateSrcset($src, $widths, 'avif');
        $webpSrcset = $this->generateSrcset($src, $widths, 'webp');
        $origSrcset = $this->generateSrcset($src, $widths);

        $html = '<picture>';

        if ($avifSrcset !== '') {
            $html .= sprintf('<source type="image/avif" srcset="%s" sizes="%s">', $avifSrcset, $sizes);
        }

        if ($webpSrcset !== '') {
            $html .= sprintf('<source type="image/webp" srcset="%s" sizes="%s">', $webpSrcset, $sizes);
        }

        // Build <img> attributes.
        $imgAttrs = [
            'src' => htmlspecialchars($src, ENT_QUOTES),
            'alt' => htmlspecialchars($alt, ENT_QUOTES),
            'loading' => $loading,
            'fetchpriority' => $fetchPriority,
            'decoding' => $decoding,
        ];

        if ($origSrcset !== '') {
            $imgAttrs['srcset'] = $origSrcset;
            $imgAttrs['sizes'] = $sizes;
        }

        if ($class !== '') {
            $imgAttrs['class'] = htmlspecialchars($class, ENT_QUOTES);
        }

        if ($width !== null) {
            $imgAttrs['width'] = (string) $width;
        }

        if ($height !== null) {
            $imgAttrs['height'] = (string) $height;
        }

        // LQIP placeholder: blur the image until it loads.
        if ($placeholder !== null) {
            $imgAttrs['data-controller'] = 'responsive-image';
            $imgAttrs['data-responsive-image-placeholder-value'] = htmlspecialchars($placeholder, ENT_QUOTES);
            $imgAttrs['style'] = sprintf(
                'background-image:url(%s);background-size:cover;filter:blur(8px);transition:filter .3s ease',
                htmlspecialchars($placeholder, ENT_QUOTES)
            );
        }

        $html .= '<img';
        foreach ($imgAttrs as $name => $value) {
            $html .= sprintf(' %s="%s"', $name, $value);
        }
        $html .= '>';
        $html .= '</picture>';

        return $html;
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build a srcset string for the given widths.
     *
     * URL convention: /images/photo.jpg → /images/photo-320w.jpg
     * This matches a common build/CDN pattern; replace buildUrl() to adapt.
     */
    private function generateSrcset(string $src, array $widths, ?string $format = null): string
    {
        $entries = [];
        foreach ($widths as $w) {
            $entries[] = $this->buildUrl($src, (int) $w, $format) . ' ' . (int) $w . 'w';
        }

        return implode(', ', $entries);
    }

    /**
     * Derive the URL for a specific width (and optional format).
     *
     * Example: /images/pizza.jpg, 640, 'webp' → /images/pizza-640w.webp
     */
    private function buildUrl(string $src, int $width, ?string $format = null): string
    {
        $info = pathinfo($src);
        $dir = ($info['dirname'] !== '.') ? rtrim($info['dirname'], '/') . '/' : '';
        $basename = $info['filename'];
        $ext = $format ?? ($info['extension'] ?? 'jpg');

        return $dir . $basename . '-' . $width . 'w.' . $ext;
    }

    /** Return true when the current request carries the Save-Data: on header. */
    private function isSaveData(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request !== null && strtolower((string) $request->headers->get('Save-Data', '')) === 'on';
    }
}
