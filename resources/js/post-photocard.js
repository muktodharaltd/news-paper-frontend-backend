const MODAL_ID = "post-photocard-modal";
const PANEL_ID = "post-photocard-panel";
const CARD_ID = "post-photocard-card";
const CARD_ALT_ID = "post-photocard-card-alt";
const VIEWPORT_ID = "post-photocard-viewport";
const VIEWPORT_ALT_ID = "post-photocard-viewport-alt";
const BODY_ID = "post-photocard-body";
const THUMB_BTN_ID = "post-photocard-thumb-btn";
const MODAL_MAX_CARD_SIZE = 660;
const THUMB_DISPLAY_SIZE = 112;
const CARD_SIZE = 1080;
const IMAGE_ASPECT = 16 / 9;
const IMAGE_HEIGHT = Math.floor((CARD_SIZE * 9) / 16);
const BOTTOM_HEIGHT = CARD_SIZE - IMAGE_HEIGHT;
const SECTION_X_PADDING = 68;
const FOOTER_BAR_X_PADDING = 36;
const TITLE_X_PADDING = 44;
const TITLE_FONT_WEIGHT = 600;
const FOOTER_HINT_SIZE = 32;
const FOOTER_HINT_SIZE_PRIMARY = 40;
const FOOTER_HINT_WEIGHT_PRIMARY = "700";
const FOOTER_HINT_TEXT_PRIMARY = "বিস্তারিত কমেন্টে ...";
const FOOTER_HINT_TEXT_ALT = "বিস্তারিত কমেন্টে";
const FOOTER_HINT_ICON_SIZE = 28;
const FOOTER_HINT_ICON_GAP = 10;
const CARET_DOUBLE_DOWN_DUOTONE = {
    fill: "M208,56l-80,80L48,56Z",
    detail: "M213.66,141.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,204.69l74.34-74.35a8,8,0,0,1,11.32,11.32Zm-171.32-80A8,8,0,0,1,48,48H208a8,8,0,0,1,5.66,13.66l-80,80a8,8,0,0,1-11.32,0Zm25,2.34L128,124.69,188.69,64Z",
};
const FOOTER_URL_SIZE_PRIMARY = 28;
const FOOTER_URL_SIZE_ALT = 28;
const FOOTER_URL_WEIGHT = "500";
const DATE_SIZE_PRIMARY = 32;
const DATE_SIZE_ALT = FOOTER_HINT_SIZE;
const LOGO_HEIGHT = 58;
const LOGO_MAX_WIDTH = 280;
const IMAGE_ICON_WATERMARK_SIZE = 112;
const IMAGE_ICON_WATERMARK_OPACITY = 0.48;
const ALT_SEAM_ICON_OPACITY = 1;
const ALT_SEAM_ICON_SIZE = 136;
const ALT_SEAM_ICON_ZOOM = 1.18;
const ALT_TITLE_TOP_GAP = 16;
const ALT_TITLE_BOTTOM_GAP = 12;
const IMAGE_ICON_PADDING = 24;
const EXPORT_SCALE = 1;
const PHOTOCARD_FONT = "SolaimanLipi, sans-serif";
const OVERLAY_DARK_SOLID = "rgba(45,5,5,1)";
const ALT_BOTTOM_BAR_BG = "rgba(96, 28, 28, 0.96)";
const ALT_BOTTOM_BAR_PADDING_Y = 14;
const BOTTOM_BOX_COLOR_TOP = OVERLAY_DARK_SOLID;
const BOTTOM_BOX_COLOR_MID = "rgba(56,12,12,1)";
const BOTTOM_BOX_COLOR_BOTTOM = "rgba(72,18,18,1)";
const ALT_BOTTOM_BOX_TOP_BORDER = 8;
const ALT_BOTTOM_BOX_TOP_BORDER_COLOR_CENTER = "#F97316";
const ALT_BOTTOM_BOX_TOP_BORDER_COLOR_EDGE = "#9A3412";
const IMAGE_OVERLAY_DARK_BAND = 0;
const IMAGE_OVERLAY_FADE_BAND = 0.18;
const AD_HEIGHT_FALLBACK = 200;
const AD_HEIGHT_MAX = 400;

function hasPhotocardAd(data) {
    return Boolean(data?.adImage);
}

function adHeightFromDimensions(width, height) {
    const w = Number(width);
    const h = Number(height);

    if (!Number.isFinite(w) || !Number.isFinite(h) || w <= 0 || h <= 0) {
        return AD_HEIGHT_FALLBACK;
    }

    return Math.max(1, Math.min(AD_HEIGHT_MAX, Math.round((CARD_SIZE * h) / w)));
}

function adHeightFromImage(adImage) {
    return adHeightFromDimensions(adImage?.naturalWidth, adImage?.naturalHeight);
}

function resolveAdHeight(data) {
    if (!hasPhotocardAd(data)) {
        return 0;
    }

    const height = Number(data.adHeight);

    return Number.isFinite(height) && height > 0
        ? Math.round(height)
        : AD_HEIGHT_FALLBACK;
}

function cardTotalHeight(data) {
    return CARD_SIZE + resolveAdHeight(data);
}

async function ensureAdHeight(data, { force = false } = {}) {
    if (!hasPhotocardAd(data)) {
        data.adHeight = 0;
        data.adHeightSource = "";
        return data;
    }

    if (
        !force &&
        data.adHeightSource === data.adImage &&
        Number.isFinite(Number(data.adHeight)) &&
        Number(data.adHeight) > 0
    ) {
        return data;
    }

    const adImage = await loadImage(data.adImage);
    data.adHeight = adHeightFromImage(adImage);
    data.adHeightSource = data.adImage;

    return data;
}

function measureAdHeightFromDom(root) {
    const img = root?.querySelector(".post-photocard-ad img");

    if (img?.naturalWidth && img?.naturalHeight) {
        return adHeightFromDimensions(img.naturalWidth, img.naturalHeight);
    }

    return 0;
}

function syncPhotocardAdLayout(root, adHeight) {
    if (!root || !(adHeight > 0)) {
        return;
    }

    const exportEl = root.querySelector(
        ".post-photocard-export, .post-photocard-export-alt",
    );
    const ad = root.querySelector(".post-photocard-ad");
    const img = ad?.querySelector("img");
    const overlay = exportEl?.querySelector(
        ':scope > [aria-hidden="true"][style*="bottom"]',
    );

    if (exportEl) {
        exportEl.style.height = `${CARD_SIZE + adHeight}px`;
    }

    if (ad) {
        ad.style.height = `${adHeight}px`;
        ad.style.minHeight = `${adHeight}px`;
        ad.style.maxHeight = `${adHeight}px`;
    }

    if (img) {
        img.style.width = `${CARD_SIZE}px`;
        img.style.height = `${adHeight}px`;
        img.style.objectFit = "fill";
        img.style.display = "block";
    }

    if (overlay) {
        overlay.style.bottom = `${adHeight}px`;
    }
}

function adBannerHtml(data) {
    if (!hasPhotocardAd(data)) {
        return "";
    }

    const height = resolveAdHeight(data);

    return `<div class="post-photocard-ad" style="width:${CARD_SIZE}px;height:${height}px;min-height:${height}px;max-height:${height}px;flex-shrink:0;overflow:hidden;background:#ffffff;line-height:0;font-size:0;"><img ${imageTagAttributes(data.adImage)} style="display:block;width:${CARD_SIZE}px;height:${height}px;object-fit:fill;"></div>`;
}

async function drawAdBanner(ctx, data) {
    if (!hasPhotocardAd(data)) {
        return;
    }

    const adImage = await loadImage(data.adImage);
    const height = adImage
        ? adHeightFromImage(adImage)
        : resolveAdHeight(data);
    data.adHeight = height;
    data.adHeightSource = data.adImage;

    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, CARD_SIZE, CARD_SIZE, height);

    if (adImage) {
        ctx.drawImage(adImage, 0, CARD_SIZE, CARD_SIZE, height);
    }
}

let currentPhotocardData = null;
let activeDesign = "alt";

function unifiedOverlayMetrics() {
    const imageDarkBand = Math.round(IMAGE_HEIGHT * IMAGE_OVERLAY_DARK_BAND);
    const imageFadeBand = Math.round(IMAGE_HEIGHT * IMAGE_OVERLAY_FADE_BAND);
    const height = BOTTOM_HEIGHT + imageDarkBand + imageFadeBand;
    const solidEnd = (BOTTOM_HEIGHT + imageDarkBand) / height;

    return { height, solidEnd };
}

function unifiedOverlayStops() {
    const { solidEnd } = unifiedOverlayMetrics();
    const darkPlateau = Math.max(0.5, solidEnd - 0.02);
    const fadeRange = 1 - solidEnd;
    const fadePos = (t) => solidEnd + fadeRange * t;

    return [
        { pos: 0, color: "rgba(96,20,20,1)" },
        { pos: 0.1, color: "rgba(86,16,16,0.98)" },
        { pos: 0.2, color: "rgba(76,12,12,0.98)" },
        { pos: 0.3, color: "rgba(66,10,10,0.99)" },
        { pos: 0.4, color: "rgba(56,8,8,0.99)" },
        { pos: 0.48, color: OVERLAY_DARK_SOLID },
        { pos: darkPlateau, color: OVERLAY_DARK_SOLID },
        { pos: solidEnd, color: OVERLAY_DARK_SOLID },
        { pos: fadePos(0.18), color: "rgba(45,5,5,0.8)" },
        { pos: fadePos(0.38), color: "rgba(45,5,5,0.45)" },
        { pos: fadePos(0.58), color: "rgba(45,5,5,0.2)" },
        { pos: fadePos(0.78), color: "rgba(45,5,5,0.07)" },
        { pos: fadePos(0.92), color: "rgba(45,5,5,0.02)" },
        { pos: 1, color: "rgba(45,5,5,0)" },
    ];
}

function unifiedOverlayGradientCss() {
    const stops = unifiedOverlayStops()
        .map(({ pos, color }) => `${color} ${Math.round(pos * 100)}%`)
        .join(", ");

    return `linear-gradient(to top, ${stops})`;
}

function isCrossOriginUrl(url) {
    try {
        const parsed = new URL(url, window.location.href);
        const pageHost = window.location.hostname.replace(/^www\./i, "");
        const assetHost = parsed.hostname.replace(/^www\./i, "");

        return (
            assetHost !== pageHost ||
            parsed.protocol !== window.location.protocol
        );
    } catch {
        return false;
    }
}

function resolveAssetUrl(url) {
    if (!url) {
        return url;
    }

    if (url.startsWith("/")) {
        return url;
    }

    try {
        const parsed = new URL(url, window.location.href);
        const pageHost = window.location.hostname.replace(/^www\./i, "");
        const assetHost = parsed.hostname.replace(/^www\./i, "");

        if (assetHost === pageHost) {
            return parsed.pathname + parsed.search;
        }
    } catch {
        return url;
    }

    return url;
}

function imageTagAttributes(url) {
    const resolved = resolveAssetUrl(url);
    const src = escapeHtml(resolved);
    const crossOrigin = isCrossOriginUrl(resolved)
        ? ' crossorigin="anonymous"'
        : "";

    return `src="${src}" alt="" decoding="sync"${crossOrigin}`;
}

function iconRenderZoom(iconImage, targetSize) {
    if (!iconImage?.naturalWidth || !iconImage?.naturalHeight) {
        return 1;
    }

    const maxDim = Math.max(iconImage.naturalWidth, iconImage.naturalHeight);
    if (maxDim >= targetSize * 0.75) {
        return ALT_SEAM_ICON_ZOOM;
    }

    return Math.min(1, maxDim / targetSize);
}

function parsePayload(button) {
    const raw = button.getAttribute("data-photocard");
    if (!raw) {
        return null;
    }

    try {
        return JSON.parse(raw);
    } catch {
        return null;
    }
}

function escapeHtml(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}

function caretDoubleDownDuotoneSvgHtml(sizePx = FOOTER_HINT_ICON_SIZE) {
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="${sizePx}" height="${sizePx}" fill="currentColor" aria-hidden="true" style="display:block;flex-shrink:0;opacity:0.95;"><path d="${CARET_DOUBLE_DOWN_DUOTONE.fill}" opacity="0.2"/><path d="${CARET_DOUBLE_DOWN_DUOTONE.detail}"/></svg>`;
}

function footerHintWithIconsHtml() {
    const icon = caretDoubleDownDuotoneSvgHtml();

    return `<div style="display:flex;align-items:center;justify-content:center;gap:${FOOTER_HINT_ICON_GAP}px;white-space:nowrap;">${icon}<div style="font-size:${FOOTER_HINT_SIZE}px;font-weight:400;line-height:1.35;opacity:0.95;">${FOOTER_HINT_TEXT_ALT}</div>${icon}</div>`;
}

function drawCaretDoubleDownDuotone(
    ctx,
    centerX,
    bottomY,
    size,
    color = "#ffffff",
) {
    const scale = size / 256;

    ctx.save();
    ctx.translate(centerX - size / 2, bottomY - size);
    ctx.scale(scale, scale);
    ctx.fillStyle = color;

    ctx.globalAlpha = 0.2;
    ctx.fill(new Path2D(CARET_DOUBLE_DOWN_DUOTONE.fill));

    ctx.globalAlpha = 0.95;
    ctx.fill(new Path2D(CARET_DOUBLE_DOWN_DUOTONE.detail));

    ctx.restore();
}

function drawFooterHintWithIcons(
    ctx,
    centerX,
    bottomY,
    {
        fontSize = FOOTER_HINT_SIZE,
        iconSize = FOOTER_HINT_ICON_SIZE,
        iconGap = FOOTER_HINT_ICON_GAP,
        text = FOOTER_HINT_TEXT_ALT,
        color = "rgba(255,255,255,0.95)",
    } = {},
) {
    ctx.save();
    ctx.fillStyle = color;
    ctx.font = `400 ${fontSize}px ${PHOTOCARD_FONT}`;
    ctx.textAlign = "left";
    ctx.textBaseline = "bottom";

    const textWidth = ctx.measureText(text).width;
    const totalWidth = iconSize * 2 + iconGap * 2 + textWidth;
    const startX = centerX - totalWidth / 2;

    drawCaretDoubleDownDuotone(
        ctx,
        startX + iconSize / 2,
        bottomY,
        iconSize,
        "#ffffff",
    );
    ctx.fillText(text, startX + iconSize + iconGap, bottomY);
    drawCaretDoubleDownDuotone(
        ctx,
        startX + iconSize + iconGap + textWidth + iconGap + iconSize / 2,
        bottomY,
        iconSize,
        "#ffffff",
    );
    ctx.restore();
}

function titleScaleFactor(dataOrScale = 100) {
    const raw =
        typeof dataOrScale === "object" && dataOrScale !== null
            ? dataOrScale.titleScale
            : dataOrScale;
    const scale = Number(raw);

    if (!Number.isFinite(scale)) {
        return 1;
    }

    return Math.max(0.9, Math.min(1.1, scale / 100));
}

function titleFontSize(title, dataOrScale = 100) {
    const length = String(title ?? "").length;
    let base;

    if (length > 120) {
        base = 56;
    } else if (length > 80) {
        base = 62;
    } else if (length > 50) {
        base = 66;
    } else {
        base = 68;
    }

    return Math.round(base * titleScaleFactor(dataOrScale));
}

function footerUrlMaxWidth(hasLogo) {
    const logoReserved = hasLogo ? LOGO_MAX_WIDTH + 16 : 0;

    return CARD_SIZE - SECTION_X_PADDING * 2 - logoReserved;
}

function setFooterUrlTextStyle(ctx, fontSize) {
    ctx.font = `${FOOTER_URL_WEIGHT} ${fontSize}px ${PHOTOCARD_FONT}`;
}

function measureFooterUrlText(ctx, text, fontSize) {
    setFooterUrlTextStyle(ctx, fontSize);

    return ctx.measureText(String(text ?? "")).width;
}

function fitFooterUrlFontSize(
    ctx,
    url,
    maxWidth,
    baseSize = FOOTER_URL_SIZE_PRIMARY,
) {
    const text = String(url ?? "");

    if (measureFooterUrlText(ctx, text, baseSize) <= maxWidth) {
        return baseSize;
    }

    return baseSize;
}

function wrapUrlLines(ctx, url, maxWidth, fontSize) {
    const text = String(url ?? "");
    if (text === "") {
        return [""];
    }

    const lines = [];
    let line = "";

    for (const char of text) {
        const nextLine = line + char;
        if (
            measureFooterUrlText(ctx, nextLine, fontSize) > maxWidth &&
            line !== ""
        ) {
            lines.push(line);
            line = char;
        } else {
            line = nextLine;
        }
    }

    if (line) {
        lines.push(line);
    }

    return lines.length > 0 ? lines : [text];
}

function footerAreaHeight(hasLogo) {
    const logoBoxHeight = LOGO_HEIGHT;
    const textHeight = FOOTER_HINT_SIZE_PRIMARY + 6;

    return Math.max(logoBoxHeight, textHeight) + 18;
}

function dateAreaHeight(hasDate) {
    return hasDate ? DATE_SIZE_PRIMARY + 18 : 0;
}

function imageWatermarkStyle(
    size = IMAGE_ICON_WATERMARK_SIZE,
    padding = IMAGE_ICON_PADDING,
    opacity = IMAGE_ICON_WATERMARK_OPACITY,
) {
    return `position:absolute;top:${padding}px;right:${padding}px;width:${size}px;height:${size}px;border-radius:9999px;object-fit:cover;z-index:3;opacity:${opacity};pointer-events:none;`;
}

function altSeamIconCenterY() {
    return IMAGE_HEIGHT;
}

function altBottomBoxTopBorderGradientCss() {
    return `linear-gradient(to right, ${ALT_BOTTOM_BOX_TOP_BORDER_COLOR_EDGE} 0%, ${ALT_BOTTOM_BOX_TOP_BORDER_COLOR_CENTER} 50%, ${ALT_BOTTOM_BOX_TOP_BORDER_COLOR_EDGE} 100%)`;
}

function photocardBottomBoxBackgroundCss() {
    return `linear-gradient(to bottom, ${BOTTOM_BOX_COLOR_TOP} 0%, ${BOTTOM_BOX_COLOR_MID} 52%, ${BOTTOM_BOX_COLOR_BOTTOM} 100%)`;
}

function drawPhotocardBottomBoxBackground(ctx) {
    const gradient = ctx.createLinearGradient(
        0,
        IMAGE_HEIGHT,
        0,
        IMAGE_HEIGHT + BOTTOM_HEIGHT,
    );
    gradient.addColorStop(0, BOTTOM_BOX_COLOR_TOP);
    gradient.addColorStop(0.52, BOTTOM_BOX_COLOR_MID);
    gradient.addColorStop(1, BOTTOM_BOX_COLOR_BOTTOM);
    ctx.fillStyle = gradient;
    ctx.fillRect(0, IMAGE_HEIGHT, CARD_SIZE, BOTTOM_HEIGHT);
}

function altBottomBoxTopBorderHtml() {
    return `<div aria-hidden="true" style="position:absolute;left:0;right:0;top:0;height:${ALT_BOTTOM_BOX_TOP_BORDER}px;background:${altBottomBoxTopBorderGradientCss()};z-index:4;pointer-events:none;"></div>`;
}

function drawAltBottomBoxTopBorder(ctx) {
    const gradient = ctx.createLinearGradient(0, 0, CARD_SIZE, 0);
    gradient.addColorStop(0, ALT_BOTTOM_BOX_TOP_BORDER_COLOR_EDGE);
    gradient.addColorStop(0.5, ALT_BOTTOM_BOX_TOP_BORDER_COLOR_CENTER);
    gradient.addColorStop(1, ALT_BOTTOM_BOX_TOP_BORDER_COLOR_EDGE);
    ctx.fillStyle = gradient;
    ctx.fillRect(0, IMAGE_HEIGHT, CARD_SIZE, ALT_BOTTOM_BOX_TOP_BORDER);
}

function altSeamIconBlockHtml(iconUrl) {
    if (!iconUrl) {
        return "";
    }

    const size = ALT_SEAM_ICON_SIZE;

    return `<div aria-hidden="true" style="position:absolute;left:50%;top:${IMAGE_HEIGHT}px;transform:translate(-50%,-50%);width:${size}px;height:${size}px;border-radius:9999px;overflow:hidden;z-index:5;pointer-events:none;"><img ${imageTagAttributes(iconUrl)} data-photocard-icon style="display:block;width:100%;height:100%;object-fit:cover;border:0;opacity:${ALT_SEAM_ICON_OPACITY};transform-origin:center center;"></div>`;
}

function drawImageWatermarkIcon(
    ctx,
    iconImage,
    position = null,
    opacity = IMAGE_ICON_WATERMARK_OPACITY,
    zoom = 1,
) {
    if (!iconImage) {
        return;
    }

    const size = position?.size ?? IMAGE_ICON_WATERMARK_SIZE;
    let centerX;
    let centerY;

    if (position) {
        centerX = position.centerX;
        centerY = position.centerY;
    } else {
        const drawX = CARD_SIZE - IMAGE_ICON_PADDING - size;
        const drawY = IMAGE_ICON_PADDING;
        centerX = drawX + size / 2;
        centerY = drawY + size / 2;
    }

    const radius = size / 2;

    ctx.save();
    ctx.globalAlpha = opacity;
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
    ctx.closePath();
    ctx.clip();

    const srcRatio = iconImage.naturalWidth / iconImage.naturalHeight;
    let sx = 0;
    let sy = 0;
    let sw = iconImage.naturalWidth;
    let sh = iconImage.naturalHeight;

    if (srcRatio > 1) {
        sw = sh;
        sx = (iconImage.naturalWidth - sw) / 2;
    } else {
        sh = sw;
        sy = (iconImage.naturalHeight - sh) / 2;
    }

    const drawSize = size * zoom;
    const zoomDrawX = centerX - drawSize / 2;
    const zoomDrawY = centerY - drawSize / 2;

    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = "high";
    ctx.drawImage(
        iconImage,
        sx,
        sy,
        sw,
        sh,
        zoomDrawX,
        zoomDrawY,
        drawSize,
        drawSize,
    );
    ctx.restore();
}

function applyIconZoomToDom(iconImage) {
    if (!iconImage?.naturalWidth) {
        return;
    }

    document.querySelectorAll("img[data-photocard-icon]").forEach((img) => {
        const zoom = iconRenderZoom(iconImage, ALT_SEAM_ICON_SIZE);
        img.style.transform = `scale(${zoom})`;
    });
}

function footerAreaHeightAlt(hasDate, urlFontSize = FOOTER_URL_SIZE_ALT) {
    const contentHeight = Math.max(
        hasDate ? DATE_SIZE_ALT : 0,
        FOOTER_HINT_SIZE,
        urlFontSize,
    );

    return contentHeight + ALT_BOTTOM_BAR_PADDING_Y * 2;
}

function altTitleBlockInsets() {
    return {
        top: Math.ceil(ALT_SEAM_ICON_SIZE / 2) + ALT_TITLE_TOP_GAP,
        bottom: footerAreaHeightAlt(true) + ALT_TITLE_BOTTOM_GAP,
    };
}

function altTitleBlockInsetsForFooter(footerReserved) {
    return {
        top: Math.ceil(ALT_SEAM_ICON_SIZE / 2) + ALT_TITLE_TOP_GAP,
        bottom: footerReserved + ALT_TITLE_BOTTOM_GAP,
    };
}

function altTitleStartY(blockTop, blockHeight, contentHeight) {
    const freeSpace = Math.max(0, blockHeight - contentHeight);
    const bias = freeSpace > 0 ? Math.round(freeSpace * 0.58) : 0;

    return blockTop + bias;
}

function buildCardHtml(data) {
    const title = escapeHtml(data.title);
    const siteName = escapeHtml(data.siteName || "");
    const date = escapeHtml(data.date || "");
    const primary = escapeHtml(data.primaryColor || "#28a745");
    const fontSize = titleFontSize(data.title, data);
    const unifiedOverlay = unifiedOverlayGradientCss();
    const { height: overlayHeight } = unifiedOverlayMetrics();

    const imageBlock = data.image
        ? `<img ${imageTagAttributes(data.image)} style="display:block;width:100%;height:100%;object-fit:cover;object-position:center top;">`
        : `<div style="width:100%;height:100%;background:linear-gradient(135deg,${primary} 0%,#0f172a 100%);"></div>`;

    const logoBlock = data.logo
        ? `<img ${imageTagAttributes(data.logo)} style="display:block;height:${LOGO_HEIGHT}px;max-width:${LOGO_MAX_WIDTH}px;object-fit:contain;">`
        : `<span style="display:block;font-size:36px;font-weight:700;color:#ffffff;line-height:1.2;text-align:right;">${siteName}</span>`;

    const dateBlock = date
        ? `<p style="position:absolute;top:16px;left:0;right:0;margin:0;text-align:center;color:#ffffff;font-size:${DATE_SIZE_PRIMARY}px;font-weight:500;line-height:1.3;text-shadow:0 1px 4px rgba(0,0,0,0.4);">${date}</p>`
        : "";

    const footerReserved = footerAreaHeight(Boolean(data.logo));
    const dateReserved = dateAreaHeight(Boolean(date));
    const titleTop = 16 + dateReserved + (date ? 8 : 0);
    const titleBottom = footerReserved + 18;

    return `
        <div class="post-photocard-export" style="position:relative;display:flex;flex-direction:column;width:${CARD_SIZE}px;height:${cardTotalHeight(data)}px;flex-shrink:0;background:${OVERLAY_DARK_SOLID};font-family:'SolaimanLipi',sans-serif;overflow:hidden;box-shadow:0 10px 40px rgba(15,23,42,0.15);">
            <div class="post-photocard-image" style="position:relative;width:${CARD_SIZE}px;height:${IMAGE_HEIGHT}px;flex-shrink:0;overflow:hidden;line-height:0;">
                ${imageBlock}
            </div>

            <div class="post-photocard-bottom" style="position:relative;width:${CARD_SIZE}px;height:${BOTTOM_HEIGHT}px;flex-shrink:0;background:${photocardBottomBoxBackgroundCss()};overflow:hidden;z-index:2;">
                <div style="position:relative;z-index:2;height:100%;box-sizing:border-box;">
                    ${dateBlock}
                    <h1 style="position:absolute;left:${TITLE_X_PADDING}px;right:${TITLE_X_PADDING}px;top:${titleTop}px;bottom:${titleBottom}px;margin:0;display:flex;align-items:center;justify-content:center;overflow:hidden;font-size:${fontSize}px;line-height:1.32;font-weight:${TITLE_FONT_WEIGHT};color:#ffffff;text-align:center;text-shadow:0 2px 8px rgba(0,0,0,0.45);">${title}</h1>
                    <div style="position:absolute;left:${SECTION_X_PADDING}px;right:${SECTION_X_PADDING}px;bottom:18px;display:flex;align-items:flex-end;justify-content:space-between;gap:16px;z-index:3;">
                        <div style="flex:1;min-width:0;text-align:left;color:#ffffff;">
                            <div style="font-size:${FOOTER_HINT_SIZE_PRIMARY}px;font-weight:${FOOTER_HINT_WEIGHT_PRIMARY};line-height:1.35;opacity:0.98;">${FOOTER_HINT_TEXT_PRIMARY}</div>
                        </div>
                        <div style="flex-shrink:0;">${logoBlock}</div>
                    </div>
                </div>
            </div>
            <div aria-hidden="true" style="position:absolute;left:0;right:0;bottom:${resolveAdHeight(data)}px;height:${overlayHeight}px;background:${unifiedOverlay};pointer-events:none;z-index:1;"></div>
            ${adBannerHtml(data)}
        </div>
    `;
}

function buildAltCardHtml(data) {
    const title = escapeHtml(data.title);
    const siteUrl = escapeHtml(data.siteUrl || "");
    const date = escapeHtml(data.date || "");
    const primary = escapeHtml(data.primaryColor || "#28a745");
    const fontSize = titleFontSize(data.title, data);
    const measureCanvas = document.createElement("canvas");
    const measureCtx = measureCanvas.getContext("2d");
    const urlFontSize = fitFooterUrlFontSize(
        measureCtx,
        data.siteUrl,
        LOGO_MAX_WIDTH,
        FOOTER_URL_SIZE_ALT,
    );

    const imageBlock = data.image
        ? `<img ${imageTagAttributes(data.image)} style="display:block;width:100%;height:100%;object-fit:cover;object-position:center top;">`
        : `<div style="width:100%;height:100%;background:linear-gradient(135deg,${primary} 0%,#0f172a 100%);"></div>`;

    const footerReserved = footerAreaHeightAlt(Boolean(date), urlFontSize);
    const titleInsets = altTitleBlockInsetsForFooter(footerReserved);
    const titleTop = titleInsets.top;
    const titleBottom = titleInsets.bottom;
    const titleMaxWidth = CARD_SIZE - TITLE_X_PADDING * 2;
    const titleLineHeight = Math.round(fontSize * 1.32);
    measureCtx.font = `${TITLE_FONT_WEIGHT} ${fontSize}px ${PHOTOCARD_FONT}`;
    const titleLines = wrapTextLines(measureCtx, data.title, titleMaxWidth);
    const titleContentHeight = titleLines.length * titleLineHeight;
    const titleBlockHeight = Math.max(
        0,
        BOTTOM_HEIGHT - titleTop - titleBottom,
    );
    const titlePaddingTop = altTitleStartY(
        0,
        titleBlockHeight,
        titleContentHeight,
    );

    const dateBlock = date
        ? `<div style="font-size:${DATE_SIZE_ALT}px;font-weight:400;line-height:1.35;opacity:0.95;color:#ffffff;text-shadow:0 1px 4px rgba(0,0,0,0.35);">${date}</div>`
        : "";

    const bottomIconBlock = altSeamIconBlockHtml(data.icon);

    return `
        <div class="post-photocard-export-alt" style="position:relative;display:flex;flex-direction:column;width:${CARD_SIZE}px;height:${cardTotalHeight(data)}px;flex-shrink:0;background:${OVERLAY_DARK_SOLID};font-family:'SolaimanLipi',sans-serif;overflow:hidden;box-shadow:0 10px 40px rgba(15,23,42,0.15);">
            <div class="post-photocard-image" style="position:relative;width:${CARD_SIZE}px;height:${IMAGE_HEIGHT}px;flex-shrink:0;overflow:hidden;line-height:0;">
                ${imageBlock}
            </div>

            <div class="post-photocard-bottom" style="position:relative;width:${CARD_SIZE}px;height:${BOTTOM_HEIGHT}px;flex-shrink:0;background:${photocardBottomBoxBackgroundCss()};overflow:hidden;z-index:2;box-sizing:border-box;">
                ${altBottomBoxTopBorderHtml()}
                <div style="position:relative;z-index:2;height:100%;box-sizing:border-box;">
                    <h1 style="position:absolute;left:${TITLE_X_PADDING}px;right:${TITLE_X_PADDING}px;top:${titleTop}px;bottom:${titleBottom}px;margin:0;display:flex;align-items:flex-start;justify-content:center;overflow:hidden;padding-top:${titlePaddingTop}px;font-size:${fontSize}px;line-height:1.32;font-weight:${TITLE_FONT_WEIGHT};color:#ffffff;text-align:center;text-shadow:0 2px 8px rgba(0,0,0,0.45);">${title}</h1>
                    <div style="position:absolute;left:0;right:0;bottom:0;z-index:3;box-sizing:border-box;background:${ALT_BOTTOM_BAR_BG};padding:${ALT_BOTTOM_BAR_PADDING_Y}px ${FOOTER_BAR_X_PADDING}px;display:flex;align-items:flex-end;justify-content:space-between;gap:12px;">
                        <div style="flex:1;min-width:0;text-align:left;color:#ffffff;">
                            ${dateBlock}
                        </div>
                        <div style="flex-shrink:0;text-align:center;color:#ffffff;">
                            ${footerHintWithIconsHtml()}
                        </div>
                        <div style="flex:1;min-width:0;text-align:right;color:#ffffff;">
                            <div style="font-size:${urlFontSize}px;font-weight:${FOOTER_URL_WEIGHT};line-height:1.25;word-break:break-all;">${siteUrl}</div>
                        </div>
                    </div>
                </div>
            </div>
            ${bottomIconBlock}
            ${adBannerHtml(data)}
        </div>
    `;
}

function buildCardHtmlForVariant(data, variant) {
    return variant === "alt" ? buildAltCardHtml(data) : buildCardHtml(data);
}

function inactiveDesign() {
    return activeDesign === "primary" ? "alt" : "primary";
}

function getThumbButton() {
    return document.getElementById(THUMB_BTN_ID);
}

function getCardAltRoot() {
    return document.getElementById(CARD_ALT_ID);
}

function getCardViewportAlt() {
    return document.getElementById(VIEWPORT_ALT_ID);
}

function getCardBody() {
    return document.getElementById(BODY_ID);
}

function getModal() {
    return document.getElementById(MODAL_ID);
}

function getCardRoot() {
    return document.getElementById(CARD_ID);
}

function getPreviewCardEl(cardRoot) {
    return cardRoot?.querySelector(
        ".post-photocard-export, .post-photocard-export-alt",
    );
}

function closeModal() {
    const modal = getModal();
    if (!modal) {
        return;
    }

    currentPhotocardData = null;
    activeDesign = "alt";
    resetModalLayout();
    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("overflow-hidden");
}

function loadImage(url) {
    const candidates = [];

    const resolved = resolveAssetUrl(url);
    if (resolved) {
        candidates.push(resolved);
        if (resolved.startsWith("/storage/meta/")) {
            candidates.push(resolved.replace("/storage/meta/", "/meta/"));
        } else if (resolved.startsWith("/meta/")) {
            candidates.push("/storage" + resolved);
        }
    }

    const tryOne = (src) =>
        new Promise((resolve) => {
            const img = new Image();
            if (isCrossOriginUrl(src)) {
                img.crossOrigin = "anonymous";
            }
            img.onload = () => resolve(img);
            img.onerror = () => resolve(null);
            img.src = src;
        });

    return (async () => {
        for (const src of candidates) {
            const img = await tryOne(src);
            if (img) {
                return img;
            }
        }

        return null;
    })();
}

function wrapTextLines(ctx, text, maxWidth) {
    const words = String(text ?? "")
        .trim()
        .split(/\s+/)
        .filter(Boolean);
    if (words.length === 0) {
        return [""];
    }

    const lines = [];
    let line = words[0];

    for (let i = 1; i < words.length; i += 1) {
        const nextLine = `${line} ${words[i]}`;
        if (ctx.measureText(nextLine).width > maxWidth) {
            lines.push(line);
            line = words[i];
        } else {
            line = nextLine;
        }
    }

    lines.push(line);
    return lines;
}

function drawCoverImage(ctx, img, destX, destY, destW, destH) {
    const srcRatio = img.naturalWidth / img.naturalHeight;
    const destRatio = destW / destH;

    let sx;
    let sy;
    let sw;
    let sh;

    if (srcRatio > destRatio) {
        sh = img.naturalHeight;
        sw = sh * destRatio;
        sx = (img.naturalWidth - sw) / 2;
        sy = 0;
    } else {
        sw = img.naturalWidth;
        sh = sw / destRatio;
        sx = 0;
        sy = 0;
    }

    ctx.drawImage(img, sx, sy, sw, sh, destX, destY, destW, destH);
}

function drawUnifiedOverlayGradient(ctx, cardHeight = CARD_SIZE) {
    drawPhotocardBottomBoxBackground(ctx);

    const { height } = unifiedOverlayMetrics();
    const topY = cardHeight - height;
    const gradient = ctx.createLinearGradient(0, cardHeight, 0, topY);

    unifiedOverlayStops().forEach(({ pos, color }) => {
        gradient.addColorStop(pos, color);
    });

    ctx.fillStyle = gradient;
    ctx.fillRect(0, topY, CARD_SIZE, height);
}

async function ensurePhotocardFont(size, weight = "700") {
    if (!document.fonts?.load) {
        return;
    }

    const spec = `${weight} ${size}px ${PHOTOCARD_FONT}`;
    await document.fonts.load(spec).catch(() => {});
}

async function renderPhotocardCanvas(data) {
    const primary = data.primaryColor || "#28a745";
    const title = String(data.title ?? "");
    const siteName = String(data.siteName ?? "");
    const date = String(data.date ?? "");
    const fontSize = titleFontSize(title, data);
    const lineHeight = Math.round(fontSize * 1.35);
    const titleMaxWidth = CARD_SIZE - TITLE_X_PADDING * 2;

    const [postImage, logoImage] = await Promise.all([
        loadImage(data.image),
        loadImage(data.logo),
    ]);

    await ensureAdHeight(data, { force: true });

    const bottomTop = IMAGE_HEIGHT;

    await ensurePhotocardFont(fontSize);
    if (date) {
        await ensurePhotocardFont(DATE_SIZE_PRIMARY, "500");
    }
    await ensurePhotocardFont(FOOTER_HINT_SIZE_PRIMARY, FOOTER_HINT_WEIGHT_PRIMARY);

    const footerHeight = footerAreaHeight(Boolean(logoImage));
    const dateHeight = dateAreaHeight(Boolean(date));
    const contentTop = bottomTop + 16;
    const titleBlockTop = contentTop + dateHeight + (date ? 8 : 0);
    const titleBlockBottom = CARD_SIZE - footerHeight - 18;
    const titleBlockHeight = Math.max(0, titleBlockBottom - titleBlockTop);

    const measureCanvas = document.createElement("canvas");
    const measureCtx = measureCanvas.getContext("2d");
    measureCtx.font = `${TITLE_FONT_WEIGHT} ${fontSize}px ${PHOTOCARD_FONT}`;
    const titleLines = wrapTextLines(measureCtx, title, titleMaxWidth);
    const titleContentHeight = titleLines.length * lineHeight;
    const titleStartY =
        titleBlockTop +
        Math.max(0, (titleBlockHeight - titleContentHeight) / 2);

    const totalHeight = cardTotalHeight(data);
    const canvas = document.createElement("canvas");
    canvas.width = Math.round(CARD_SIZE * EXPORT_SCALE);
    canvas.height = Math.round(totalHeight * EXPORT_SCALE);

    const ctx = canvas.getContext("2d");
    ctx.scale(EXPORT_SCALE, EXPORT_SCALE);

    if (postImage) {
        drawCoverImage(ctx, postImage, 0, 0, CARD_SIZE, IMAGE_HEIGHT);
    } else {
        const gradient = ctx.createLinearGradient(
            0,
            0,
            CARD_SIZE,
            IMAGE_HEIGHT,
        );
        gradient.addColorStop(0, primary);
        gradient.addColorStop(1, "#0f172a");
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, CARD_SIZE, IMAGE_HEIGHT);
    }

    drawUnifiedOverlayGradient(ctx, CARD_SIZE);

    if (date) {
        ctx.fillStyle = "#ffffff";
        ctx.font = `500 ${DATE_SIZE_PRIMARY}px ${PHOTOCARD_FONT}`;
        ctx.textAlign = "center";
        ctx.textBaseline = "top";
        ctx.shadowColor = "rgba(0,0,0,0.4)";
        ctx.shadowBlur = 4;
        ctx.shadowOffsetY = 1;
        ctx.fillText(date, CARD_SIZE / 2, contentTop);
        ctx.shadowColor = "transparent";
        ctx.shadowBlur = 0;
        ctx.shadowOffsetY = 0;
    }

    ctx.fillStyle = "#ffffff";
    ctx.font = `${TITLE_FONT_WEIGHT} ${fontSize}px ${PHOTOCARD_FONT}`;
    ctx.textAlign = "center";
    ctx.textBaseline = "top";
    ctx.shadowColor = "rgba(0,0,0,0.45)";
    ctx.shadowBlur = 8;
    ctx.shadowOffsetY = 2;

    titleLines.forEach((line, index) => {
        ctx.fillText(line, CARD_SIZE / 2, titleStartY + index * lineHeight);
    });

    ctx.shadowColor = "transparent";
    ctx.shadowBlur = 0;
    ctx.shadowOffsetY = 0;

    const footerBaseY = CARD_SIZE - 18;

    ctx.textAlign = "left";
    ctx.textBaseline = "bottom";
    ctx.fillStyle = "rgba(255,255,255,0.98)";
    ctx.font = `${FOOTER_HINT_WEIGHT_PRIMARY} ${FOOTER_HINT_SIZE_PRIMARY}px ${PHOTOCARD_FONT}`;
    ctx.letterSpacing = "0px";
    ctx.fillText(FOOTER_HINT_TEXT_PRIMARY, SECTION_X_PADDING, footerBaseY);

    if (logoImage) {
        const logoHeight = LOGO_HEIGHT;
        const logoWidth = Math.min(
            LOGO_MAX_WIDTH,
            logoImage.naturalWidth * (logoHeight / logoImage.naturalHeight),
        );
        const logoX = CARD_SIZE - SECTION_X_PADDING - logoWidth;
        const logoY = footerBaseY - logoHeight;

        ctx.drawImage(logoImage, logoX, logoY, logoWidth, logoHeight);
    } else if (siteName) {
        ctx.fillStyle = "#ffffff";
        ctx.font = `700 36px ${PHOTOCARD_FONT}`;
        ctx.textAlign = "right";
        ctx.textBaseline = "bottom";
        ctx.fillText(siteName, CARD_SIZE - SECTION_X_PADDING, footerBaseY);
    }

    await drawAdBanner(ctx, data);

    return canvas;
}

async function renderAltPhotocardCanvas(data) {
    const primary = data.primaryColor || "#28a745";
    const title = String(data.title ?? "");
    const siteUrl = String(data.siteUrl ?? "");
    const date = String(data.date ?? "");
    const fontSize = titleFontSize(title, data);
    const lineHeight = Math.round(fontSize * 1.35);
    const titleMaxWidth = CARD_SIZE - TITLE_X_PADDING * 2;

    const [postImage, iconImage] = await Promise.all([
        loadImage(data.image),
        loadImage(data.icon),
    ]);

    await ensureAdHeight(data, { force: true });

    const bottomTop = IMAGE_HEIGHT;

    await ensurePhotocardFont(fontSize);
    if (date) {
        await ensurePhotocardFont(DATE_SIZE_ALT, "400");
    }
    await ensurePhotocardFont(FOOTER_HINT_SIZE, "400");
    await ensurePhotocardFont(FOOTER_URL_SIZE_ALT, FOOTER_URL_WEIGHT);

    const measureCanvas = document.createElement("canvas");
    const measureCtx = measureCanvas.getContext("2d");
    const urlFontSize = fitFooterUrlFontSize(
        measureCtx,
        siteUrl,
        LOGO_MAX_WIDTH,
        FOOTER_URL_SIZE_ALT,
    );
    await ensurePhotocardFont(urlFontSize, FOOTER_URL_WEIGHT);

    const footerHeight = footerAreaHeightAlt(Boolean(date), urlFontSize);
    const titleBlockTop = bottomTop + Math.ceil(ALT_SEAM_ICON_SIZE / 2) + ALT_TITLE_TOP_GAP;
    const titleBlockBottom = CARD_SIZE - footerHeight - ALT_TITLE_BOTTOM_GAP;
    const titleBlockHeight = Math.max(0, titleBlockBottom - titleBlockTop);

    measureCtx.font = `${TITLE_FONT_WEIGHT} ${fontSize}px ${PHOTOCARD_FONT}`;
    const titleLines = wrapTextLines(measureCtx, title, titleMaxWidth);
    const titleContentHeight = titleLines.length * lineHeight;
    const titleStartY = altTitleStartY(
        titleBlockTop,
        titleBlockHeight,
        titleContentHeight,
    );

    const totalHeight = cardTotalHeight(data);
    const canvas = document.createElement("canvas");
    canvas.width = Math.round(CARD_SIZE * EXPORT_SCALE);
    canvas.height = Math.round(totalHeight * EXPORT_SCALE);

    const ctx = canvas.getContext("2d");
    ctx.scale(EXPORT_SCALE, EXPORT_SCALE);

    if (postImage) {
        drawCoverImage(ctx, postImage, 0, 0, CARD_SIZE, IMAGE_HEIGHT);
    } else {
        const gradient = ctx.createLinearGradient(
            0,
            0,
            CARD_SIZE,
            IMAGE_HEIGHT,
        );
        gradient.addColorStop(0, primary);
        gradient.addColorStop(1, "#0f172a");
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, CARD_SIZE, IMAGE_HEIGHT);
    }

    drawPhotocardBottomBoxBackground(ctx);

    drawAltBottomBoxTopBorder(ctx);

    drawImageWatermarkIcon(
        ctx,
        iconImage,
        {
            centerX: CARD_SIZE / 2,
            centerY: altSeamIconCenterY(),
            size: ALT_SEAM_ICON_SIZE,
        },
        ALT_SEAM_ICON_OPACITY,
        iconImage
            ? iconRenderZoom(iconImage, ALT_SEAM_ICON_SIZE)
            : ALT_SEAM_ICON_ZOOM,
    );

    ctx.fillStyle = "#ffffff";
    ctx.font = `${TITLE_FONT_WEIGHT} ${fontSize}px ${PHOTOCARD_FONT}`;
    ctx.textAlign = "center";
    ctx.textBaseline = "top";
    ctx.shadowColor = "rgba(0,0,0,0.45)";
    ctx.shadowBlur = 8;
    ctx.shadowOffsetY = 2;

    titleLines.forEach((line, index) => {
        ctx.fillText(line, CARD_SIZE / 2, titleStartY + index * lineHeight);
    });

    ctx.shadowColor = "transparent";
    ctx.shadowBlur = 0;
    ctx.shadowOffsetY = 0;

    const barHeight = footerHeight;
    const barTop = CARD_SIZE - barHeight;
    ctx.fillStyle = ALT_BOTTOM_BAR_BG;
    ctx.fillRect(0, barTop, CARD_SIZE, barHeight);

    const footerBaseY = CARD_SIZE - ALT_BOTTOM_BAR_PADDING_Y;

    if (date) {
        ctx.fillStyle = "#ffffff";
        ctx.font = `400 ${DATE_SIZE_ALT}px ${PHOTOCARD_FONT}`;
        ctx.textAlign = "left";
        ctx.textBaseline = "bottom";
        ctx.shadowColor = "rgba(0,0,0,0.4)";
        ctx.shadowBlur = 4;
        ctx.shadowOffsetY = 1;
        ctx.fillText(date, FOOTER_BAR_X_PADDING, footerBaseY);
        ctx.shadowColor = "transparent";
        ctx.shadowBlur = 0;
        ctx.shadowOffsetY = 0;
    }

    drawFooterHintWithIcons(ctx, CARD_SIZE / 2, footerBaseY);

    const urlLines = wrapUrlLines(
        measureCtx,
        siteUrl,
        LOGO_MAX_WIDTH,
        urlFontSize,
    );
    const urlLineHeight = Math.round(urlFontSize * 1.25);

    ctx.fillStyle = "#ffffff";
    setFooterUrlTextStyle(ctx, urlFontSize);
    ctx.textAlign = "right";
    ctx.textBaseline = "bottom";
    urlLines.forEach((line, index) => {
        const lineY =
            footerBaseY - (urlLines.length - 1 - index) * urlLineHeight;
        ctx.fillText(line, CARD_SIZE - FOOTER_BAR_X_PADDING, lineY);
    });
    ctx.letterSpacing = "0px";

    await drawAdBanner(ctx, data);

    return canvas;
}

function replaceBrokenImages(container, primaryColor = "#28a745") {
    const images = Array.from(container.querySelectorAll("img")).filter(
        (img) =>
            !img.getAttribute("aria-hidden") &&
            !img.closest(".post-photocard-ad"),
    );

    images.forEach((img) => {
        if (img.complete && img.naturalWidth > 0) {
            return;
        }

        const placeholder = document.createElement("div");
        placeholder.style.cssText =
            "width:100%;height:100%;background:linear-gradient(135deg," +
            primaryColor +
            " 0%,#0f172a 100%);";
        img.replaceWith(placeholder);
    });
}

function downloadBlob(blob, filename) {
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.download = filename;
    link.href = url;
    link.style.display = "none";
    document.body.appendChild(link);
    link.click();

    setTimeout(() => {
        URL.revokeObjectURL(url);
        link.remove();
    }, 1000);
}

async function canvasToBlob(canvas) {
    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => {
            if (blob) {
                resolve(blob);
                return;
            }

            reject(new Error("Failed to create image blob"));
        }, "image/png");
    });
}

function sanitizeFilenamePart(value, fallback = "image") {
    const cleaned = String(value ?? "")
        .replace(/\.[^./\\]+$/u, "")
        .replace(/[^a-z0-9-_]+/gi, "-")
        .replace(/^-+|-+$/g, "")
        .toLowerCase();

    return cleaned || fallback;
}

function buildDownloadFilename(data, variant = "primary") {
    const postId = sanitizeFilenamePart(
        data.id ?? data.postId ?? "post",
        "post",
    );

    if (variant === "alt") {
        return `photo-card-${postId}-design-2.png`;
    }

    return `photo-card-${postId}.png`;
}

async function downloadAltCard(data, downloadBtn = null) {
    const modal = getModal();
    const btn =
        downloadBtn || modal?.querySelector("[data-photocard-download]");

    if (btn && !downloadBtn) {
        btn.disabled = true;
    }

    try {
        const canvas = await renderAltPhotocardCanvas(data);
        const blob = await canvasToBlob(canvas);
        downloadBlob(blob, buildDownloadFilename(data, "alt"));
    } catch (error) {
        console.error("Alt photocard download failed:", error);
        window.alert(
            "ডিজাইন ২ ডাউনলোড করা যায়নি। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।",
        );
        throw error;
    } finally {
        if (btn && !downloadBtn) {
            btn.disabled = false;
        }
    }
}

async function waitForImages(container) {
    const images = Array.from(container.querySelectorAll("img")).filter(
        (img) => !img.getAttribute("aria-hidden"),
    );

    await Promise.all(
        images.map((img) => {
            if (img.complete) {
                return Promise.resolve();
            }

            return new Promise((resolve) => {
                const done = () => resolve();
                img.addEventListener("load", done, { once: true });
                img.addEventListener("error", done, { once: true });
            });
        }),
    );
}

async function renderPreview(data) {
    const cardRoot = getCardRoot();
    const cardAltRoot = getCardAltRoot();

    if (!cardRoot || !cardAltRoot) {
        return;
    }

    await ensureAdHeight(data, { force: true });

    cardRoot.innerHTML = buildCardHtmlForVariant(data, activeDesign);
    cardAltRoot.innerHTML = buildCardHtmlForVariant(data, inactiveDesign());

    await Promise.all([waitForImages(cardRoot), waitForImages(cardAltRoot)]);

    const measured =
        measureAdHeightFromDom(cardRoot) ||
        measureAdHeightFromDom(cardAltRoot) ||
        resolveAdHeight(data);

    if (hasPhotocardAd(data) && measured > 0) {
        data.adHeight = measured;
        data.adHeightSource = data.adImage;
        syncPhotocardAdLayout(cardRoot, measured);
        syncPhotocardAdLayout(cardAltRoot, measured);
    }

    const iconImage = await loadImage(data.icon);
    if (iconImage) {
        applyIconZoomToDom(iconImage);
    }

    replaceBrokenImages(cardRoot, data.primaryColor);
    replaceBrokenImages(cardAltRoot, data.primaryColor);

    await new Promise((resolve) =>
        requestAnimationFrame(() => requestAnimationFrame(resolve)),
    );
    applyPreviewScale();
}

async function swapActiveDesign() {
    if (!currentPhotocardData) {
        return;
    }

    activeDesign = inactiveDesign();
    await renderPreview(currentPhotocardData);
}

function ensurePreviewScaler(cardRoot, cardEl) {
    let scaler = cardRoot.querySelector(".post-photocard-scaler");

    if (!scaler) {
        scaler = document.createElement("div");
        scaler.className = "post-photocard-scaler";
        scaler.style.overflow = "hidden";
        scaler.style.lineHeight = "0";
        cardRoot.insertBefore(scaler, cardEl);
        scaler.appendChild(cardEl);
    }

    return scaler;
}

function scalePreviewCard(cardRoot, cardEl, displayWidth, cardHeight = CARD_SIZE) {
    const previewScaler = ensurePreviewScaler(cardRoot, cardEl);
    const displayScale = displayWidth / CARD_SIZE;
    const displayHeight = Math.max(1, Math.round(cardHeight * displayScale));

    previewScaler.style.width = `${displayWidth}px`;
    previewScaler.style.height = `${displayHeight}px`;
    previewScaler.style.overflow = "hidden";

    cardRoot.style.display = "block";
    cardRoot.style.lineHeight = "0";
    cardRoot.style.width = `${displayWidth}px`;
    cardRoot.style.height = `${displayHeight}px`;
    cardRoot.style.overflow = "hidden";

    cardEl.style.width = `${CARD_SIZE}px`;
    cardEl.style.height = `${cardHeight}px`;
    cardEl.style.transform = `scale(${displayScale})`;
    cardEl.style.transformOrigin = "top left";
}

function getModalPanel() {
    return document.getElementById(PANEL_ID);
}

function resetModalLayout() {
    const panel = getModalPanel();
    const viewport = getCardViewport();
    const viewportAlt = getCardViewportAlt();
    const body = getCardBody();

    if (panel) {
        panel.style.width = "";
        panel.style.height = "";
        panel.style.maxWidth = "";
    }

    if (body) {
        body.style.width = "";
        body.style.height = "";
        body.style.gap = "";
        body.style.padding = "";
        body.style.position = "";
        body.style.display = "";
        body.style.flexDirection = "";
        body.style.alignItems = "";
    }

    [viewport, viewportAlt].forEach((node) => {
        if (!node) {
            return;
        }

        node.style.width = "";
        node.style.height = "";
        node.style.flex = "";
        node.style.margin = "";
        node.style.marginLeft = "";
        node.style.marginRight = "";
    });

    const thumbBtn = getThumbButton();
    if (thumbBtn) {
        thumbBtn.style.width = "";
        thumbBtn.style.height = "";
        thumbBtn.style.padding = "";
        thumbBtn.style.top = "";
        thumbBtn.style.right = "";
        thumbBtn.style.position = "";
    }
}

function getCardViewport() {
    return document.getElementById(VIEWPORT_ID);
}

function applyPreviewScale() {
    const cardRoot = getCardRoot();
    const cardAltRoot = getCardAltRoot();
    const cardEl = getPreviewCardEl(cardRoot);
    const cardAltEl = getPreviewCardEl(cardAltRoot);
    const viewport = getCardViewport();
    const viewportAlt = getCardViewportAlt();
    const panel = getModalPanel();
    const body = getCardBody();
    const thumbBtn = getThumbButton();

    if (!cardEl || !cardAltEl || !viewport || !viewportAlt || !panel) {
        return;
    }

    [cardRoot, cardAltRoot].forEach((root) => {
        const scaler = root?.querySelector(".post-photocard-scaler");
        const el = getPreviewCardEl(root);

        if (el) {
            el.style.transform = "";
            el.style.transformOrigin = "";
        }

        if (root) {
            root.style.width = "";
            root.style.height = "";
            root.style.overflow = "";
        }

        if (scaler) {
            scaler.style.width = "";
            scaler.style.height = "";
        }
    });

    const header = panel.querySelector("[data-photocard-header]");
    const headerHeight = header?.offsetHeight ?? 52;
    const modalGutter = 40;
    const bodyPaddingX = 0; // big card is edge-to-edge — no left/right gap
    const bodyPaddingY = 12; // only bottom room for thumb
    const bodyGap = 12;
    const refMain = MODAL_MAX_CARD_SIZE;
    const refThumb = THUMB_DISPLAY_SIZE;
    const refPanelWidth = refMain + bodyPaddingX;
    const cardHeight = cardTotalHeight(currentPhotocardData);
    const heightRatio = cardHeight / CARD_SIZE;

    const viewportWidth = window.visualViewport?.width ?? window.innerWidth;
    const viewportHeight = window.visualViewport?.height ?? window.innerHeight;
    const availableWidth = viewportWidth - modalGutter;
    const availableHeight =
        viewportHeight - modalGutter - headerHeight - bodyPaddingY;

    // Reserve vertical space for the alternate design thumb under the main card.
    const thumbPad = 4;
    const thumbBorder = 2;
    const refThumbSlotH =
        Math.round(refThumb * heightRatio) + thumbPad * 2 + thumbBorder * 2;
    const heightForMain = Math.max(
        120,
        availableHeight - bodyGap - refThumbSlotH,
    );

    const layoutScale = Math.min(
        1,
        availableWidth / Math.max(1, refPanelWidth),
        heightForMain / (refMain * heightRatio),
    );

    const mainScaled = Math.max(1, Math.floor(refMain * layoutScale));
    const mainHeight = Math.max(1, Math.round(mainScaled * heightRatio));
    const thumbScaled = Math.max(1, Math.floor(refThumb * layoutScale));
    const thumbHeight = Math.max(1, Math.round(thumbScaled * heightRatio));
    const scaledThumbPad = Math.max(1, Math.ceil(thumbPad * layoutScale));
    const scaledThumbBorder = Math.max(2, Math.ceil(thumbBorder * layoutScale));
    const thumbSlot = thumbScaled + scaledThumbPad * 2 + scaledThumbBorder * 2;
    const thumbSlotH = thumbHeight + scaledThumbPad * 2 + scaledThumbBorder * 2;
    const scaledBodyGap = Math.max(8, Math.ceil(bodyGap * layoutScale));
    const panelWidth = mainScaled;

    panel.style.width = `${panelWidth}px`;
    panel.style.maxWidth = `${availableWidth}px`;
    panel.style.height = "";

    if (body) {
        body.style.width = `${panelWidth}px`;
        body.style.height = "";
        body.style.gap = `${scaledBodyGap}px`;
        body.style.padding = "0";
        body.style.position = "";
        body.style.display = "flex";
        body.style.flexDirection = "column";
        body.style.alignItems = "stretch";
    }

    viewport.style.width = `${mainScaled}px`;
    viewport.style.height = `${mainHeight}px`;
    viewport.style.flex = "0 0 auto";
    viewport.style.margin = "0";
    viewport.style.marginLeft = "0";
    viewport.style.marginRight = "0";

    viewportAlt.style.width = `${thumbScaled}px`;
    viewportAlt.style.height = `${thumbHeight}px`;
    viewportAlt.style.flex = "0 0 auto";

    if (thumbBtn) {
        thumbBtn.style.width = `${thumbSlot}px`;
        thumbBtn.style.height = `${thumbSlotH}px`;
        thumbBtn.style.padding = `${scaledThumbPad}px`;
        thumbBtn.style.top = "";
        thumbBtn.style.right = "";
        thumbBtn.style.position = "";
    }

    scalePreviewCard(cardRoot, cardEl, mainScaled, cardHeight);
    scalePreviewCard(cardAltRoot, cardAltEl, thumbScaled, cardHeight);
}

async function downloadCard(data) {
    const modal = getModal();
    const downloadBtn = modal?.querySelector("[data-photocard-download]");

    if (downloadBtn) {
        downloadBtn.disabled = true;
    }

    try {
        if (activeDesign === "alt") {
            await downloadAltCard(data, downloadBtn);
            return;
        }

        const cardRoot = getCardRoot();
        if (
            !cardRoot?.querySelector(".post-photocard-export") &&
            !cardRoot?.querySelector(".post-photocard-export-alt")
        ) {
            window.alert("ফটোকার্ড তৈরি করা যায়নি। আবার চেষ্টা করুন।");
            return;
        }

        const canvas = await renderPhotocardCanvas(data);
        const blob = await canvasToBlob(canvas);
        downloadBlob(blob, buildDownloadFilename(data, "primary"));
    } catch (error) {
        console.error("Photocard download failed:", error);
        window.alert(
            "ফটোকার্ড ডাউনলোড করা যায়নি। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।",
        );
    } finally {
        if (downloadBtn) {
            downloadBtn.disabled = false;
        }
    }
}

function openModal(button) {
    const data = parsePayload(button);
    const modal = getModal();

    if (!data || !modal) {
        return;
    }

    currentPhotocardData = data;
    delete currentPhotocardData.adHeight;
    delete currentPhotocardData.adHeightSource;
    activeDesign = "alt";
    modal.classList.remove("hidden");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("overflow-hidden");

    void renderPreview(data);
}

function ensureModalOnBody() {
    const modal = getModal();
    if (modal && modal.parentElement !== document.body) {
        // Modal must not stay under a display:none ancestor (e.g. desktop-only share row).
        document.body.appendChild(modal);
    }
}

function initPostPhotocard() {
    ensureModalOnBody();

    document.addEventListener("click", (event) => {
        const openBtn = event.target.closest(".post-photocard-open");
        if (openBtn) {
            event.preventDefault();
            ensureModalOnBody();
            openModal(openBtn);
            return;
        }

        const downloadBtn = event.target.closest("[data-photocard-download]");
        if (downloadBtn) {
            event.preventDefault();
            if (currentPhotocardData) {
                void downloadCard(currentPhotocardData);
            }
            return;
        }

        const thumbBtn = event.target.closest("#post-photocard-thumb-btn");
        if (thumbBtn) {
            event.preventDefault();
            void swapActiveDesign();
            return;
        }

        if (event.target.closest("[data-photocard-close]")) {
            event.preventDefault();
            closeModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeModal();
        }
    });

    window.addEventListener("resize", () => {
        if (currentPhotocardData && !getModal()?.classList.contains("hidden")) {
            applyPreviewScale();
        }
    });

    window.visualViewport?.addEventListener("resize", () => {
        if (currentPhotocardData && !getModal()?.classList.contains("hidden")) {
            applyPreviewScale();
        }
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPostPhotocard);
} else {
    initPostPhotocard();
}
