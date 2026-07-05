/**
 * Google AdSense — slot-এর ভিতরে clamp (local ad size-এর বেশি হবে না)।
 */
(function () {
    const isMobile = () => window.matchMedia('(max-width: 767px)').matches;

    function frameLimit(frame, mobile) {
        const styles = getComputedStyle(frame);
        const key = mobile ? '--ad-mobile-max-height' : '--ad-max-height';
        const raw = styles.getPropertyValue(key).trim();
        const parsed = parseInt(raw, 10);

        return Number.isFinite(parsed) && parsed > 0 ? parsed : 90;
    }

    function frameWidthLimit(frame, mobile) {
        const styles = getComputedStyle(frame);
        const key = mobile ? '--ad-mobile-max-width' : '--ad-max-width';
        const raw = styles.getPropertyValue(key).trim();
        const parsed = parseInt(raw, 10);

        if (Number.isFinite(parsed) && parsed > 0) {
            return parsed;
        }

        return frame?.clientWidth || frame?.offsetWidth || 320;
    }

    function frameWidth(frame) {
        let w = frame?.clientWidth || 0;

        if (w > 0) {
            return w;
        }

        w = frame?.offsetWidth || 0;
        if (w > 0) {
            return w;
        }

        const parent = frame?.parentElement;
        if (parent) {
            w = parent.clientWidth || parent.offsetWidth || 0;
            if (w > 0) {
                return w;
            }
        }

        return frame?.getAttribute('data-ad-layout') === 'box' ? 300 : 320;
    }

    function boxHeightFromAspect(frame, width, capH) {
        const styles = getComputedStyle(frame);
        const ar = styles.aspectRatio;

        if (ar && ar !== 'auto') {
            if (ar.includes('/')) {
                const parts = ar.split('/').map((part) => parseFloat(part.trim()));

                if (parts.length === 2 && parts[0] > 0 && parts[1] > 0) {
                    return Math.min(capH, Math.round(width * (parts[1] / parts[0])));
                }
            } else {
                const ratio = parseFloat(ar);

                if (Number.isFinite(ratio) && ratio > 0) {
                    return Math.min(capH, Math.round(width / ratio));
                }
            }
        }

        const maxW = parseInt(styles.getPropertyValue('--ad-max-width'), 10);
        const maxH = parseInt(styles.getPropertyValue('--ad-max-height'), 10);

        if (maxW > 0 && maxH > 0) {
            return Math.min(capH, Math.round(width * (maxH / maxW)));
        }

        return Math.min(capH, Math.round(width * 0.75));
    }

    function isFixedStripFrame(frame) {
        return (
            frame?.getAttribute('data-ad-layout') !== 'box' &&
            Boolean(frame?.closest('[data-ad-below-menu], #header-ad-slot'))
        );
    }

    function frameMetrics(frame, mobile) {
        const layout = frame.getAttribute('data-ad-layout') || 'strip';
        const capH = frameLimit(frame, mobile);
        const capW = Math.min(frameWidth(frame), frameWidthLimit(frame, mobile));
        const width = capW;

        if (isFixedStripFrame(frame)) {
            const height = mobile ? boxHeightFromAspect(frame, width, capH) : capH;

            return { width, height, layout };
        }

        if (layout === 'box') {
            const height = boxHeightFromAspect(frame, width, capH);

            return { width, height, layout };
        }

        return { width, height: capH, layout };
    }

    function fitInside(adW, adH, maxW, maxH) {
        if (adW < 1 || adH < 1) {
            return {
                width: Math.max(1, Math.min(maxW, maxW)),
                height: Math.max(1, Math.min(maxH, maxH)),
            };
        }

        const scale = Math.min(maxW / adW, maxH / adH, 1);

        return {
            width: Math.max(1, Math.round(adW * scale)),
            height: Math.max(1, Math.round(adH * scale)),
        };
    }

    function prepareBoxFrame(frame, height) {
        if (frame.getAttribute('data-ad-layout') !== 'box' || height <= 0) {
            return;
        }

        frame.style.height = height + 'px';
        frame.style.minHeight = height + 'px';
        frame.style.maxHeight = height + 'px';
    }

    function prepareStripFrame(frame, height) {
        if (frame.getAttribute('data-ad-layout') === 'box' || height <= 0) {
            return;
        }

        frame.style.height = height + 'px';
        frame.style.minHeight = height + 'px';
        frame.style.maxHeight = height + 'px';
    }

    function prepareFilledFrame(ins, frame, height, layout) {
        if (layout === 'box') {
            prepareBoxFrame(frame, height);
        } else if (isFixedStripFrame(frame)) {
            prepareStripFrame(frame, height);
        } else {
            return;
        }

        ins.style.display = 'flex';
        ins.style.alignItems = 'center';
        ins.style.justifyContent = 'center';
        ins.style.width = '100%';
        ins.style.overflow = 'hidden';

        if (isFixedStripFrame(frame)) {
            ins.style.position = 'absolute';
            ins.style.inset = '0';
            ins.style.height = '100%';
            ins.style.minHeight = '100%';
            ins.style.maxHeight = '100%';
        } else {
            ins.style.position = 'relative';
            ins.style.height = height + 'px';
            ins.style.minHeight = height + 'px';
            ins.style.maxHeight = height + 'px';
        }
    }

    function readIframeSize(iframe, fallbackW, fallbackH) {
        let adW = iframe.offsetWidth;
        let adH = iframe.offsetHeight;

        if (!adW || !adH) {
            adW = parseInt(iframe.getAttribute('width'), 10) || fallbackW;
            adH = parseInt(iframe.getAttribute('height'), 10) || fallbackH;
        }

        if (adW < 1 || adH < 1) {
            adW = fallbackW;
            adH = fallbackH;
        }

        return { adW, adH };
    }

    function centerIframe(iframe, width, height) {
        iframe.style.position = 'relative';
        iframe.style.left = 'auto';
        iframe.style.top = 'auto';
        iframe.style.right = 'auto';
        iframe.style.transform = 'none';
        iframe.style.width = width + 'px';
        iframe.style.height = height + 'px';
        iframe.style.maxWidth = width + 'px';
        iframe.style.maxHeight = height + 'px';
        iframe.style.marginInline = 'auto';
        iframe.style.display = 'block';
        iframe.style.border = '0';
    }

    function fitStripIframe(iframe, frame, targetH) {
        const frameW = Math.min(frame.clientWidth || frameWidth(frame), frameWidthLimit(frame, isMobile()));
        const frameH = Math.min(
            frame.clientHeight > 0 ? frame.clientHeight : targetH,
            targetH,
        );

        if (frameW < 1 || frameH < 1) {
            return;
        }

        const { adW, adH } = readIframeSize(iframe, frameW, frameH);
        const fitted = fitInside(adW, adH, frameW, frameH);

        centerIframe(iframe, fitted.width, fitted.height);
    }

    function syncGoogleStripHosts(ins) {
        if (!ins) {
            return;
        }

        ins.style.display = 'flex';
        ins.style.alignItems = 'center';
        ins.style.justifyContent = 'center';

        ins.querySelectorAll('div').forEach((host) => {
            host.style.display = 'flex';
            host.style.alignItems = 'center';
            host.style.justifyContent = 'center';
            host.style.position = 'relative';
            host.style.inset = 'auto';
            host.style.width = '100%';
            host.style.maxWidth = '100%';
            host.style.height = '100%';
            host.style.maxHeight = '100%';
            host.style.overflow = 'hidden';
            host.style.margin = '0 auto';
        });
    }

    function fitFixedStripIframe(iframe, frame, targetH) {
        const frameW = Math.min(frame.clientWidth || frameWidth(frame), frameWidthLimit(frame, isMobile()));
        const frameH = Math.min(
            frame.clientHeight > 0 ? frame.clientHeight : targetH,
            targetH,
        );

        if (frameW < 1 || frameH < 1) {
            return;
        }

        const { adW, adH } = readIframeSize(iframe, frameW, Math.round(frameW / 13));
        const fitted = fitInside(adW, adH, frameW, frameH);

        const ins = iframe.closest('ins.adsbygoogle');
        syncGoogleStripHosts(ins);

        centerIframe(iframe, fitted.width, fitted.height);
    }

    function fitFilledIframe(iframe, frame, targetH, layout) {
        if (layout === 'box') {
            fitBoxIframe(iframe, frame, targetH);
            return;
        }

        if (isFixedStripFrame(frame)) {
            fitFixedStripIframe(iframe, frame, targetH);
            return;
        }

        fitStripIframe(iframe, frame, targetH);
    }

    function fitBoxIframe(iframe, frame, targetH) {
        const frameW = Math.min(frame.clientWidth || frameWidth(frame), frameWidthLimit(frame, isMobile()));
        const frameH = Math.min(
            frame.clientHeight > 0 ? frame.clientHeight : targetH,
            targetH,
        );

        if (frameW < 1 || frameH < 1) {
            return;
        }

        const { adW, adH } = readIframeSize(iframe, frameW, frameH);
        const fitted = fitInside(adW, adH, frameW, frameH);

        centerIframe(iframe, fitted.width, fitted.height);
    }

    function clampIframe(ins) {
        const frame = ins.closest('.ad-slot-frame');
        const iframe = ins.querySelector('iframe');
        if (!frame || !iframe) {
            return false;
        }

        const { width, height, layout } = frameMetrics(frame, isMobile());

        iframe.style.display = 'block';

        if (layout === 'box' || isFixedStripFrame(frame)) {
            prepareFilledFrame(ins, frame, height, layout);
            fitFilledIframe(iframe, frame, height, layout);
        } else {
            prepareStripFrame(frame, height);
            fitStripIframe(iframe, frame, height);
        }

        return true;
    }

    function watchUnit(ins) {
        if (ins.getAttribute('data-ad-watched') === '1') {
            return;
        }
        ins.setAttribute('data-ad-watched', '1');

        let done = false;
        const finish = () => {
            if (done) {
                return;
            }
            if (clampIframe(ins)) {
                done = true;
                mo.disconnect();
            }
        };

        const mo = new MutationObserver(finish);
        mo.observe(ins, { childList: true, subtree: true });

        finish();

        window.requestAnimationFrame(() => clampIframe(ins));
        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => clampIframe(ins));
        });
    }

    function clampAllBoxAds() {
        document.querySelectorAll('ins.adsbygoogle[data-ad-client]').forEach((ins) => {
            clampIframe(ins);
        });
    }

    function boot() {
        document.querySelectorAll('ins.adsbygoogle[data-ad-client]:not([data-ad-watched])').forEach((ins) => {
            watchUnit(ins);
        });
    }

    boot();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot, { once: true });
    }

    let resizeTimer = 0;
    window.addEventListener('resize', () => {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(clampAllBoxAds, 150);
    });
})();
