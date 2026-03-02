<?php

namespace App\Tests\Twig\Extension;

use App\Twig\Extension\ResponsiveImageExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ResponsiveImageExtensionTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function makeExtension(?Request $request = null): ResponsiveImageExtension
    {
        $stack = new RequestStack();
        if ($request !== null) {
            $stack->push($request);
        }

        return new ResponsiveImageExtension($stack);
    }

    // -------------------------------------------------------------------------
    // getFunctions()
    // -------------------------------------------------------------------------

    public function testGetFunctionsRegistersResponsiveImage(): void
    {
        $ext = $this->makeExtension();
        $names = array_map(fn ($f) => $f->getName(), $ext->getFunctions());

        self::assertContains('responsive_image', $names);
    }

    // -------------------------------------------------------------------------
    // Basic output structure
    // -------------------------------------------------------------------------

    public function testOutputContainsPictureElement(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza');

        self::assertStringContainsString('<picture>', $html);
        self::assertStringContainsString('</picture>', $html);
    }

    public function testOutputContainsImgElement(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza');

        self::assertStringContainsString('<img', $html);
        self::assertStringContainsString('alt="Pizza"', $html);
        self::assertStringContainsString('src="/images/pizza.jpg"', $html);
    }

    public function testOutputContainsWebpSource(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza');

        self::assertStringContainsString('type="image/webp"', $html);
    }

    public function testOutputContainsAvifSource(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza');

        self::assertStringContainsString('type="image/avif"', $html);
    }

    // -------------------------------------------------------------------------
    // srcset / sizes
    // -------------------------------------------------------------------------

    public function testSrcsetContainsDefaultWidths(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza');

        self::assertStringContainsString('320w', $html);
        self::assertStringContainsString('640w', $html);
        self::assertStringContainsString('960w', $html);
        self::assertStringContainsString('1280w', $html);
    }

    public function testCustomWidthsAreRespected(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/img/pizza.jpg', 'Pizza', ['widths' => [200, 400]]);

        self::assertStringContainsString('200w', $html);
        self::assertStringContainsString('400w', $html);
        self::assertStringNotContainsString('960w', $html);
    }

    public function testCustomSizesIsIncluded(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/img/pizza.jpg', 'Pizza', ['sizes' => '100vw']);

        self::assertStringContainsString('100vw', $html);
    }

    // -------------------------------------------------------------------------
    // Lazy-loading
    // -------------------------------------------------------------------------

    public function testLazyLoadingIsDefaultForNonCriticalImages(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza');

        self::assertStringContainsString('loading="lazy"', $html);
    }

    public function testCriticalImageUsesEagerLoading(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza', ['critical' => true]);

        self::assertStringContainsString('loading="eager"', $html);
        self::assertStringNotContainsString('loading="lazy"', $html);
    }

    public function testCriticalImageHasHighFetchPriority(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza', ['critical' => true]);

        self::assertStringContainsString('fetchpriority="high"', $html);
    }

    public function testLazyOptionFalseDisablesLazyLoading(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/images/pizza.jpg', 'Pizza', ['lazy' => false]);

        self::assertStringContainsString('loading="eager"', $html);
    }

    // -------------------------------------------------------------------------
    // Optional attributes
    // -------------------------------------------------------------------------

    public function testWidthAndHeightAttributesPreventCls(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/img/p.jpg', 'P', ['width' => 800, 'height' => 600]);

        self::assertStringContainsString('width="800"', $html);
        self::assertStringContainsString('height="600"', $html);
    }

    public function testClassAttributeIsIncluded(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/img/p.jpg', 'P', ['class' => 'hero-img']);

        self::assertStringContainsString('class="hero-img"', $html);
    }

    // -------------------------------------------------------------------------
    // LQIP placeholder
    // -------------------------------------------------------------------------

    public function testPlaceholderAddsBlurStyleAndStimulusController(): void
    {
        $ext = $this->makeExtension();
        $placeholder = 'data:image/jpeg;base64,/9j/abc';
        $html = $ext->renderResponsiveImage('/img/p.jpg', 'P', ['placeholder' => $placeholder]);

        self::assertStringContainsString('data-controller="responsive-image"', $html);
        self::assertStringContainsString('filter:blur(8px)', $html);
        self::assertStringContainsString('transition:filter .3s ease', $html);
    }

    // -------------------------------------------------------------------------
    // Save-Data behaviour
    // -------------------------------------------------------------------------

    public function testSaveDataHeaderReducesSrcsetToSmallestWidth(): void
    {
        $request = new Request();
        $request->headers->set('Save-Data', 'on');

        $ext = $this->makeExtension($request);
        $html = $ext->renderResponsiveImage('/img/pizza.jpg', 'Pizza', ['widths' => [320, 640, 960]]);

        // Only the smallest width should remain.
        self::assertStringContainsString('320w', $html);
        self::assertStringNotContainsString('640w', $html);
        self::assertStringNotContainsString('960w', $html);
    }

    public function testSaveDataDoesNotReduceCriticalImages(): void
    {
        $request = new Request();
        $request->headers->set('Save-Data', 'on');

        $ext = $this->makeExtension($request);
        $html = $ext->renderResponsiveImage('/img/pizza.jpg', 'Pizza', [
            'widths' => [320, 640, 960],
            'critical' => true,
        ]);

        // Critical images keep all widths.
        self::assertStringContainsString('320w', $html);
        self::assertStringContainsString('640w', $html);
        self::assertStringContainsString('960w', $html);
    }

    public function testNoSaveDataHeaderKeepsFullSrcset(): void
    {
        $request = new Request();
        // No Save-Data header set.

        $ext = $this->makeExtension($request);
        $html = $ext->renderResponsiveImage('/img/pizza.jpg', 'Pizza', ['widths' => [320, 640, 960]]);

        self::assertStringContainsString('320w', $html);
        self::assertStringContainsString('640w', $html);
        self::assertStringContainsString('960w', $html);
    }

    // -------------------------------------------------------------------------
    // Alt text escaping
    // -------------------------------------------------------------------------

    public function testAltTextIsHtmlEscaped(): void
    {
        $ext = $this->makeExtension();
        $html = $ext->renderResponsiveImage('/img/p.jpg', '<script>alert(1)</script>');

        self::assertStringNotContainsString('<script>', $html);
        self::assertStringContainsString('&lt;script&gt;', $html);
    }
}
