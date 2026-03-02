import { Controller } from '@hotwired/stimulus';

/*
 * Theme controller – animated sun/moon toggle.
 *
 * Toggles between light and dark mode. When no explicit choice
 * has been saved, the system preference (prefers-color-scheme) is used.
 *
 * Usage:
 *   <div data-controller="theme">
 *     <button data-theme-target="toggle" data-action="click->theme#toggle" aria-label="Changer de thème">
 *       …
 *     </button>
 *   </div>
 */
export default class extends Controller {
    static targets = ['toggle'];

    connect() {
        this._onSystemChange = () => this.#syncVisual();
        this._mq = window.matchMedia('(prefers-color-scheme: dark)');
        this._mq.addEventListener('change', this._onSystemChange);

        // Apply saved theme to <html> (may already be set by inline script)
        const stored = localStorage.getItem('theme');
        if (stored === 'dark' || stored === 'light') {
            document.documentElement.setAttribute('data-theme', stored);
        }

        this.#syncVisual();
    }

    disconnect() {
        this._mq?.removeEventListener('change', this._onSystemChange);
    }

    toggle() {
        const newTheme = this.#isDark() ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        document.documentElement.setAttribute('data-theme', newTheme);
        this.#syncVisual();
    }

    /** Is dark mode effectively active? */
    #isDark() {
        const stored = localStorage.getItem('theme');
        if (stored === 'dark') return true;
        if (stored === 'light') return false;
        return this._mq.matches;           // system preference
    }

    /** Keep the toggle button visual in sync with the effective theme. */
    #syncVisual() {
        this.toggleTarget.classList.toggle('is-dark', this.#isDark());
    }
}
