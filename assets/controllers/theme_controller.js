import { Controller } from '@hotwired/stimulus';

/*
 * Theme controller – handles system/light/dark mode switching.
 *
 * Usage:
 *   <div data-controller="theme">
 *     <select data-theme-target="selector" data-action="change->theme#change">
 *       <option value="system">Système</option>
 *       <option value="light">Clair</option>
 *       <option value="dark">Sombre</option>
 *     </select>
 *   </div>
 */
export default class extends Controller {
    static targets = ['selector'];

    connect() {
        const stored = localStorage.getItem('theme') || 'system';
        this.selectorTarget.value = stored;
        this.#applyTheme(stored);
    }

    change(event) {
        const theme = event.target.value;
        this.#applyTheme(theme);
        localStorage.setItem('theme', theme);
    }

    #applyTheme(theme) {
        if (theme === 'dark' || theme === 'light') {
            document.documentElement.setAttribute('data-theme', theme);
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
    }
}
