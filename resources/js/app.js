import './bootstrap';
import './post-photocard';
import './web-push';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

import './adsense-lazy';

/** Cache থাকলে img আগেই loaded — placeholder তখনও সরিয়ে দিন */
function resolveImgPlaceholders() {
    document.querySelectorAll('.img-placeholder img, .img-placeholder video').forEach((el) => {
        const parent = el.closest('.img-placeholder');
        if (!parent) {
            return;
        }
        if (el.complete && (el.naturalWidth > 0 || el.readyState >= 2)) {
            parent.classList.remove('img-placeholder');
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', resolveImgPlaceholders, { once: true });
} else {
    resolveImgPlaceholders();
}
