<?php

declare(strict_types=1);

namespace App\Tests\Accessibility;

use PHPUnit\Framework\TestCase;

/**
 * Validates WCAG 2.1 AA accessibility requirements in the Twig templates.
 *
 * These tests parse the raw template source (not rendered HTML) and check for
 * the presence of the required accessibility attributes and structures.
 */
class BaseTemplateAccessibilityTest extends TestCase
{
    private string $baseTemplatePath;
    private string $baseTemplateContent;
    private string $headerPartialPath;
    private string $headerPartialContent;

    protected function setUp(): void
    {
        $this->baseTemplatePath = dirname(__DIR__, 2) . '/templates/base.html.twig';
        $this->assertFileExists($this->baseTemplatePath, 'base.html.twig must exist');
        $this->baseTemplateContent = file_get_contents($this->baseTemplatePath);

        $this->headerPartialPath = dirname(__DIR__, 2) . '/templates/partials/_header.html.twig';
        $this->assertFileExists($this->headerPartialPath, '_header.html.twig must exist');
        $this->headerPartialContent = file_get_contents($this->headerPartialPath);
    }

    /** The <html> element must declare a language (lang attribute). */
    public function testHtmlElementHasLangAttribute(): void
    {
        $this->assertMatchesRegularExpression(
            '/<html[^>]+lang=["\'][a-z]{2}/i',
            $this->baseTemplateContent,
            'The <html> element must have a lang attribute (WCAG 3.1.1)'
        );
    }

    /** The page must include a viewport meta tag for mobile accessibility. */
    public function testViewportMetaTagIsPresent(): void
    {
        $this->assertStringContainsString(
            'name="viewport"',
            $this->baseTemplateContent,
            'A <meta name="viewport"> tag must be present'
        );
    }

    /** A skip link pointing to #main-content must be present (WCAG 2.4.1). */
    public function testSkipLinkIsPresent(): void
    {
        $this->assertStringContainsString(
            'href="#main-content"',
            $this->baseTemplateContent,
            'A skip link pointing to #main-content must be present (WCAG 2.4.1)'
        );
    }

    /** The skip link must have visible text so screen-reader users can activate it. */
    public function testSkipLinkHasText(): void
    {
        $this->assertMatchesRegularExpression(
            '/href="#main-content"[^>]*>[^<]+</i',
            $this->baseTemplateContent,
            'The skip link must contain visible text'
        );
    }

    /** The charset meta must be UTF-8 (HTML5 requirement for a11y tools). */
    public function testCharsetIsUtf8(): void
    {
        $this->assertMatchesRegularExpression(
            '/<meta[^>]+charset=["\']?UTF-8/i',
            $this->baseTemplateContent,
            'The charset must be declared as UTF-8'
        );
    }

    /** The header partial must have a <header> landmark element. */
    public function testHeaderLandmarkIsPresent(): void
    {
        $this->assertMatchesRegularExpression(
            '/<header[\s>]/',
            $this->headerPartialContent,
            'The header partial must contain a <header> landmark (WCAG 1.3.6)'
        );
    }

    /** The nav must have an accessible label (WCAG 2.4.6). */
    public function testNavHasAccessibleLabel(): void
    {
        $this->assertMatchesRegularExpression(
            '/<nav[^>]+(aria-label|aria-labelledby)=["\'][^"\']+["\']/',
            $this->headerPartialContent,
            '<nav> must have an aria-label or aria-labelledby (WCAG 2.4.6)'
        );
    }

    /** Each page template's main content area must have id="main-content". */
    public function testPageTemplatesHaveMainContentId(): void
    {
        $pageTemplatesDir = dirname(__DIR__, 2) . '/templates/pages';
        $this->assertDirectoryExists($pageTemplatesDir);

        foreach (glob($pageTemplatesDir . '/*.html.twig') as $pagePath) {
            $pageContent = file_get_contents($pagePath);
            $this->assertStringContainsString(
                'id="main-content"',
                $pageContent,
                sprintf('Page template %s must define the #main-content anchor (WCAG 2.4.1)', basename($pagePath))
            );
        }
    }

    /** Each page template with a main content area must have tabindex="-1" for skip link focus. */
    public function testPageTemplatesMainHasTabindex(): void
    {
        $pageTemplatesDir = dirname(__DIR__, 2) . '/templates/pages';
        $this->assertDirectoryExists($pageTemplatesDir);

        foreach (glob($pageTemplatesDir . '/*.html.twig') as $pagePath) {
            $pageContent = file_get_contents($pagePath);
            $this->assertStringContainsString(
                'tabindex="-1"',
                $pageContent,
                sprintf('Page template %s must have tabindex="-1" on the main content element (WCAG 2.4.1)', basename($pagePath))
            );
        }
    }

    /** The CSS must define skip-link styles. */
    public function testSkipLinkStylesAreDefined(): void
    {
        $cssPath = dirname(__DIR__, 2) . '/assets/styles/app.css';
        $this->assertFileExists($cssPath, 'app.css must exist');

        $cssContent = file_get_contents($cssPath);
        $this->assertStringContainsString(
            '.skip-link',
            $cssContent,
            'app.css must define .skip-link styles for the skip navigation link (WCAG 2.4.1)'
        );
    }

    /** The CSS must define focus-visible styles for keyboard navigation. */
    public function testFocusVisibleStylesAreDefined(): void
    {
        $cssPath = dirname(__DIR__, 2) . '/assets/styles/app.css';
        $this->assertFileExists($cssPath, 'app.css must exist');

        $cssContent = file_get_contents($cssPath);
        $this->assertStringContainsString(
            ':focus-visible',
            $cssContent,
            'app.css must define :focus-visible styles for visible keyboard focus indicators (WCAG 2.4.7)'
        );
    }
}
