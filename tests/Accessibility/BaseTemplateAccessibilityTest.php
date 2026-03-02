<?php

declare(strict_types=1);

namespace App\Tests\Accessibility;

use PHPUnit\Framework\TestCase;

/**
 * Validates WCAG 2.1 AA accessibility requirements in the base Twig template.
 *
 * These tests parse the raw template source (not rendered HTML) and check for
 * the presence of the required accessibility attributes and structures.
 */
class BaseTemplateAccessibilityTest extends TestCase
{
    private string $templatePath;
    private string $templateContent;

    protected function setUp(): void
    {
        $this->templatePath = dirname(__DIR__, 2) . '/templates/base.html.twig';
        $this->assertFileExists($this->templatePath, 'base.html.twig must exist');
        $this->templateContent = file_get_contents($this->templatePath);
    }

    /** The <html> element must declare a language (lang attribute). */
    public function testHtmlElementHasLangAttribute(): void
    {
        $this->assertMatchesRegularExpression(
            '/<html[^>]+lang=["\'][a-z]{2}/i',
            $this->templateContent,
            'The <html> element must have a lang attribute (WCAG 3.1.1)'
        );
    }

    /** The page must include a viewport meta tag for mobile accessibility. */
    public function testViewportMetaTagIsPresent(): void
    {
        $this->assertStringContainsString(
            'name="viewport"',
            $this->templateContent,
            'A <meta name="viewport"> tag must be present'
        );
    }

    /** A skip link pointing to #main-content must be present (WCAG 2.4.1). */
    public function testSkipLinkIsPresent(): void
    {
        $this->assertStringContainsString(
            'href="#main-content"',
            $this->templateContent,
            'A skip link pointing to #main-content must be present (WCAG 2.4.1)'
        );
    }

    /** The skip link must have visible text so screen-reader users can activate it. */
    public function testSkipLinkHasText(): void
    {
        $this->assertMatchesRegularExpression(
            '/href="#main-content"[^>]*>[^<]+</i',
            $this->templateContent,
            'The skip link must contain visible text'
        );
    }

    /** The <main> landmark must have the id used by the skip link. */
    public function testMainElementHasCorrectId(): void
    {
        $this->assertMatchesRegularExpression(
            '/<main[^>]+id=["\']main-content["\']/',
            $this->templateContent,
            '<main id="main-content"> must be present as the skip-link target'
        );
    }

    /** The <main> landmark must have tabindex="-1" so it can receive focus on skip. */
    public function testMainElementHasTabindexForSkipLink(): void
    {
        $this->assertMatchesRegularExpression(
            '/<main[^>]+tabindex=["\']?-1["\']?/',
            $this->templateContent,
            '<main> must have tabindex="-1" to receive programmatic focus from the skip link (WCAG 2.4.1)'
        );
    }

    /** A <header> landmark element must be present (WCAG 1.3.6 / 4.1.2). */
    public function testHeaderLandmarkIsPresent(): void
    {
        $this->assertMatchesRegularExpression(
            '/<header[\s>]/',
            $this->templateContent,
            'A <header> landmark must be present (WCAG 1.3.6)'
        );
    }

    /** A <main> landmark element must be present (WCAG 1.3.6). */
    public function testMainLandmarkIsPresent(): void
    {
        $this->assertMatchesRegularExpression(
            '/<main[\s>]/',
            $this->templateContent,
            'A <main> landmark must be present (WCAG 1.3.6)'
        );
    }

    /** A <footer> landmark element must be present (WCAG 1.3.6). */
    public function testFooterLandmarkIsPresent(): void
    {
        $this->assertMatchesRegularExpression(
            '/<footer[\s>]/',
            $this->templateContent,
            'A <footer> landmark must be present (WCAG 1.3.6)'
        );
    }

    /** The <nav> element must have an accessible label (WCAG 2.4.6). */
    public function testNavHasAccessibleLabel(): void
    {
        $this->assertMatchesRegularExpression(
            '/<nav[^>]+(aria-label|aria-labelledby)=["\'][^"\']+["\']/',
            $this->templateContent,
            '<nav> must have an aria-label or aria-labelledby (WCAG 2.4.6)'
        );
    }

    /** The charset meta must be UTF-8 (HTML5 requirement for a11y tools). */
    public function testCharsetIsUtf8(): void
    {
        $this->assertMatchesRegularExpression(
            '/<meta[^>]+charset=["\']?UTF-8/i',
            $this->templateContent,
            'The charset must be declared as UTF-8'
        );
    }
}
