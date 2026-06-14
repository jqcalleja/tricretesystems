<?php

/**
 * svg_helper.php — Tricrete Systems
 *
 * All SVG logos and icons are centralized here.
 * Autoloaded via app/Config/Autoload.php:
 *   public $helpers = ['url', 'form', 'svg'];
 *
 * Usage:
 *   echo svg_logo();                      // full logo: mark + wordmark
 *   echo svg_logo_mark();                 // mark only (icon)
 *   echo svg_icon('employees', 'me-2');   // named UI icon
 */

// ============================================================
// LOGO MARK (the interlocking Z/S block cluster)
// ============================================================

/**
 * Returns the Tricrete interlocking block mark as inline SVG.
 * This is the icon-only version (no wordmark).
 *
 * @param string $class  CSS class(es) to apply to the <svg> element
 * @param string $size   Width and height (square). Default '32'.
 */
function svg_logo_mark(string $class = '', string $size = '32'): string
{
    $c = htmlspecialchars($class);
    $s = htmlspecialchars($size);

    return <<<SVG
<svg width="{$s}" height="{$s}" viewBox="3 3 354 354"
     xmlns="http://www.w3.org/2000/svg"
     class="{$c}" aria-label="Tricrete" role="img">
  <g fill="#1D7A1D">
    <rect x="123" y="3"   width="54" height="24"/>
    <rect x="93"  y="33"  width="54" height="24"/>
    <rect x="153" y="3"   width="54" height="24"/>
    <rect x="183" y="33"  width="54" height="24"/>
    <rect x="243" y="3"   width="24" height="54"/>
    <rect x="273" y="33"  width="24" height="54"/>
    <rect x="63"  y="33"  width="24" height="54"/>
    <rect x="33"  y="63"  width="24" height="54"/>
    <rect x="123" y="63"  width="54" height="24"/>
    <rect x="93"  y="93"  width="54" height="24"/>
    <rect x="153" y="33"  width="54" height="24"/>
    <rect x="183" y="63"  width="54" height="24"/>
    <rect x="243" y="33"  width="24" height="54"/>
    <rect x="213" y="63"  width="24" height="54"/>
    <rect x="303" y="33"  width="54" height="24"/>
    <rect x="273" y="63"  width="54" height="24"/>
    <rect x="3"   y="93"  width="24" height="54"/>
    <rect x="33"  y="123" width="24" height="54"/>
    <rect x="63"  y="93"  width="54" height="24"/>
    <rect x="93"  y="123" width="54" height="24"/>
    <rect x="153" y="93"  width="54" height="24"/>
    <rect x="123" y="123" width="54" height="24"/>
    <rect x="213" y="93"  width="24" height="54"/>
    <rect x="183" y="123" width="24" height="54"/>
    <rect x="243" y="93"  width="54" height="24"/>
    <rect x="213" y="123" width="54" height="24"/>
    <rect x="303" y="63"  width="24" height="54"/>
    <rect x="273" y="93"  width="24" height="54"/>
    <rect x="303" y="93"  width="24" height="54"/>
    <rect x="333" y="123" width="24" height="54"/>
    <rect x="33"  y="153" width="54" height="24"/>
    <rect x="63"  y="183" width="54" height="24"/>
    <rect x="93"  y="153" width="54" height="24"/>
    <rect x="183" y="123" width="24" height="54"/>
    <rect x="153" y="153" width="24" height="54"/>
    <rect x="183" y="153" width="54" height="24"/>
    <rect x="213" y="183" width="54" height="24"/>
    <rect x="243" y="123" width="24" height="54"/>
    <rect x="273" y="153" width="24" height="54"/>
    <rect x="3"   y="153" width="24" height="54"/>
    <rect x="33"  y="183" width="24" height="54"/>
    <rect x="93"  y="213" width="54" height="24"/>
    <rect x="153" y="183" width="54" height="24"/>
    <rect x="123" y="213" width="54" height="24"/>
    <rect x="183" y="183" width="54" height="24"/>
    <rect x="213" y="213" width="54" height="24"/>
    <rect x="273" y="183" width="24" height="54"/>
    <rect x="303" y="213" width="24" height="54"/>
    <rect x="63"  y="213" width="24" height="54"/>
    <rect x="33"  y="243" width="24" height="54"/>
    <rect x="93"  y="243" width="54" height="24"/>
    <rect x="63"  y="273" width="54" height="24"/>
    <rect x="153" y="243" width="54" height="24"/>
    <rect x="183" y="213" width="24" height="54"/>
    <rect x="213" y="243" width="24" height="54"/>
    <rect x="213" y="243" width="54" height="24"/>
    <rect x="243" y="273" width="54" height="24"/>
    <rect x="93"  y="273" width="54" height="24"/>
    <rect x="63"  y="303" width="54" height="24"/>
    <rect x="153" y="273" width="24" height="54"/>
    <rect x="123" y="303" width="24" height="54"/>
    <rect x="183" y="303" width="54" height="24"/>
    <rect x="153" y="333" width="54" height="24"/>
    <rect x="243" y="273" width="24" height="54"/>
    <rect x="213" y="303" width="24" height="54"/>
  </g>
</svg>
SVG;
}

// ============================================================
// FULL LOGO (mark + wordmark)
// ============================================================

/**
 * Returns the full Tricrete Systems logo: mark + wordmark stacked beside it.
 *
 * @param string $class   CSS class(es) on the outer <svg>
 * @param string $height  Height of the logo. Width is auto. Default '40'.
 * @param bool   $dark    If true, renders wordmark in white (for dark backgrounds)
 */
function svg_logo(string $class = '', string $height = '40', bool $dark = false): string
{
    $c      = htmlspecialchars($class);
    $h      = htmlspecialchars($height);
    $name   = $dark ? '#FFFFFF' : '#1A1A1A';
    $sub    = $dark ? 'rgba(255,255,255,0.5)' : '#6B7280';

    // viewBox: mark 354x354 units, text beside it
    // Total canvas: 780 wide x 354 tall (mark=354, gap=30, text area=396)
    return <<<SVG
<svg width="auto" height="{$h}" viewBox="0 0 780 354"
     xmlns="http://www.w3.org/2000/svg"
     class="{$c}" aria-label="Tricrete Systems" role="img">

  <!-- Mark -->
  <g fill="#1D7A1D">
    <rect x="123" y="3"   width="54" height="24"/>
    <rect x="93"  y="33"  width="54" height="24"/>
    <rect x="153" y="3"   width="54" height="24"/>
    <rect x="183" y="33"  width="54" height="24"/>
    <rect x="243" y="3"   width="24" height="54"/>
    <rect x="273" y="33"  width="24" height="54"/>
    <rect x="63"  y="33"  width="24" height="54"/>
    <rect x="33"  y="63"  width="24" height="54"/>
    <rect x="123" y="63"  width="54" height="24"/>
    <rect x="93"  y="93"  width="54" height="24"/>
    <rect x="153" y="33"  width="54" height="24"/>
    <rect x="183" y="63"  width="54" height="24"/>
    <rect x="243" y="33"  width="24" height="54"/>
    <rect x="213" y="63"  width="24" height="54"/>
    <rect x="303" y="33"  width="54" height="24"/>
    <rect x="273" y="63"  width="54" height="24"/>
    <rect x="3"   y="93"  width="24" height="54"/>
    <rect x="33"  y="123" width="24" height="54"/>
    <rect x="63"  y="93"  width="54" height="24"/>
    <rect x="93"  y="123" width="54" height="24"/>
    <rect x="153" y="93"  width="54" height="24"/>
    <rect x="123" y="123" width="54" height="24"/>
    <rect x="213" y="93"  width="24" height="54"/>
    <rect x="183" y="123" width="24" height="54"/>
    <rect x="243" y="93"  width="54" height="24"/>
    <rect x="213" y="123" width="54" height="24"/>
    <rect x="303" y="63"  width="24" height="54"/>
    <rect x="273" y="93"  width="24" height="54"/>
    <rect x="303" y="93"  width="24" height="54"/>
    <rect x="333" y="123" width="24" height="54"/>
    <rect x="33"  y="153" width="54" height="24"/>
    <rect x="63"  y="183" width="54" height="24"/>
    <rect x="93"  y="153" width="54" height="24"/>
    <rect x="183" y="123" width="24" height="54"/>
    <rect x="153" y="153" width="24" height="54"/>
    <rect x="183" y="153" width="54" height="24"/>
    <rect x="213" y="183" width="54" height="24"/>
    <rect x="243" y="123" width="24" height="54"/>
    <rect x="273" y="153" width="24" height="54"/>
    <rect x="3"   y="153" width="24" height="54"/>
    <rect x="33"  y="183" width="24" height="54"/>
    <rect x="93"  y="213" width="54" height="24"/>
    <rect x="153" y="183" width="54" height="24"/>
    <rect x="123" y="213" width="54" height="24"/>
    <rect x="183" y="183" width="54" height="24"/>
    <rect x="213" y="213" width="54" height="24"/>
    <rect x="273" y="183" width="24" height="54"/>
    <rect x="303" y="213" width="24" height="54"/>
    <rect x="63"  y="213" width="24" height="54"/>
    <rect x="33"  y="243" width="24" height="54"/>
    <rect x="93"  y="243" width="54" height="24"/>
    <rect x="63"  y="273" width="54" height="24"/>
    <rect x="153" y="243" width="54" height="24"/>
    <rect x="183" y="213" width="24" height="54"/>
    <rect x="213" y="243" width="24" height="54"/>
    <rect x="213" y="243" width="54" height="24"/>
    <rect x="243" y="273" width="54" height="24"/>
    <rect x="93"  y="273" width="54" height="24"/>
    <rect x="63"  y="303" width="54" height="24"/>
    <rect x="153" y="273" width="24" height="54"/>
    <rect x="123" y="303" width="24" height="54"/>
    <rect x="183" y="303" width="54" height="24"/>
    <rect x="153" y="333" width="54" height="24"/>
    <rect x="243" y="273" width="24" height="54"/>
    <rect x="213" y="303" width="24" height="54"/>
  </g>

  <!-- Wordmark: vertically centered beside the mark -->
  <!-- TRICRETE - large bold -->
  <text x="390" y="195"
        font-family="Arial Black, Arial, sans-serif"
        font-weight="900"
        font-size="148"
        fill="{$name}"
        dominant-baseline="middle"
        letter-spacing="4">TRICRETE</text>

  <!-- SYSTEMS - small caps subtitle -->
  <text x="394" y="295"
        font-family="Arial, sans-serif"
        font-weight="400"
        font-size="62"
        fill="{$sub}"
        letter-spacing="18">SYSTEMS</text>

</svg>
SVG;
}

// ============================================================
// NAMED ICONS
// ============================================================

/**
 * Returns a named SVG icon (stroke-based, currentColor).
 *
 * @param string $name   Icon name (see list below)
 * @param string $class  CSS class(es) on the <svg>
 * @param string $size   Width and height in px. Default '18'.
 */
function svg_icon(string $name, string $class = '', string $size = '18'): string
{
    $c = htmlspecialchars($class);
    $s = htmlspecialchars($size);

    $attrs = "width=\"{$s}\" height=\"{$s}\" viewBox=\"0 0 24 24\" fill=\"none\" "
        . "stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" "
        . "stroke-linejoin=\"round\" class=\"{$c}\" aria-hidden=\"true\"";

    $icons = [

        // ── Navigation ──────────────────────────────────────
        'dashboard' =>
        '<rect x="3" y="3" width="7" height="7"/>'
            . '<rect x="14" y="3" width="7" height="7"/>'
            . '<rect x="14" y="14" width="7" height="7"/>'
            . '<rect x="3" y="14" width="7" height="7"/>',

        'employees' =>
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>'
            . '<circle cx="9" cy="7" r="4"/>'
            . '<path d="M23 21v-2a4 4 0 0 0-3-3.87"/>'
            . '<path d="M16 3.13a4 4 0 0 1 0 7.75"/>',

        'attendance' =>
        '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>'
            . '<line x1="16" y1="2" x2="16" y2="6"/>'
            . '<line x1="8" y1="2" x2="8" y2="6"/>'
            . '<line x1="3" y1="10" x2="21" y2="10"/>'
            . '<polyline points="9 16 11 18 15 14"/>',

        'projects' =>
        '<polygon points="12 2 2 7 12 12 22 7 12 2"/>'
            . '<polyline points="2 17 12 22 22 17"/>'
            . '<polyline points="2 12 12 17 22 12"/>',

        'reports' =>
        '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>'
            . '<polyline points="14 2 14 8 20 8"/>'
            . '<line x1="16" y1="13" x2="8" y2="13"/>'
            . '<line x1="16" y1="17" x2="8" y2="17"/>'
            . '<polyline points="10 9 9 9 8 9"/>',

        'users' =>
        '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>'
            . '<circle cx="12" cy="7" r="4"/>'
            . '<line x1="18" y1="8" x2="23" y2="13"/>'
            . '<line x1="23" y1="8" x2="18" y2="13"/>',

        // ── Tools & Equipment ────────────────────────────────
        'equipment' =>
        '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',

        'inventory' =>
        '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'
            . '<polyline points="3.27 6.96 12 12.01 20.73 6.96"/>'
            . '<line x1="12" y1="22.08" x2="12" y2="12"/>',

        'assign' =>
        '<polyline points="17 1 21 5 17 9"/>'
            . '<path d="M3 11V9a4 4 0 0 1 4-4h14"/>'
            . '<polyline points="7 23 3 19 7 15"/>'
            . '<path d="M21 13v2a4 4 0 0 1-4 4H3"/>',

        // ── Accounting ───────────────────────────────────────
        'accounting' =>
        '<line x1="12" y1="1" x2="12" y2="23"/>'
            . '<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',

        'journal' =>
        '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>'
            . '<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',

        'chart-of-accounts' =>
        '<line x1="8" y1="6" x2="21" y2="6"/>'
            . '<line x1="8" y1="12" x2="21" y2="12"/>'
            . '<line x1="8" y1="18" x2="21" y2="18"/>'
            . '<line x1="3" y1="6" x2="3.01" y2="6"/>'
            . '<line x1="3" y1="12" x2="3.01" y2="12"/>'
            . '<line x1="3" y1="18" x2="3.01" y2="18"/>',

        // ── System / UI ──────────────────────────────────────
        'menu' =>
        '<line x1="3" y1="12" x2="21" y2="12"/>'
            . '<line x1="3" y1="6" x2="21" y2="6"/>'
            . '<line x1="3" y1="18" x2="21" y2="18"/>',

        'bell' =>
        '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>'
            . '<path d="M13.73 21a2 2 0 0 1-3.46 0"/>',

        'search' =>
        '<circle cx="11" cy="11" r="8"/>'
            . '<line x1="21" y1="21" x2="16.65" y2="16.65"/>',

        'settings' =>
        '<circle cx="12" cy="12" r="3"/>'
            . '<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06'
            . 'a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09'
            . 'A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83'
            . 'l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09'
            . 'A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83'
            . 'l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09'
            . 'a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83'
            . 'l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09'
            . 'a1.65 1.65 0 0 0-1.51 1z"/>',

        'logout' =>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>'
            . '<polyline points="16 17 21 12 16 7"/>'
            . '<line x1="21" y1="12" x2="9" y2="12"/>',

        'user-circle' =>
        '<circle cx="12" cy="12" r="10"/>'
            . '<circle cx="12" cy="10" r="3"/>'
            . '<path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/>',

        'chevron-down' =>
        '<polyline points="6 9 12 15 18 9"/>',

        'chevron-right' =>
        '<polyline points="9 18 15 12 9 6"/>',

        'chevron-left' =>
        '<polyline points="15 18 9 12 15 6"/>',

        // ── Actions ──────────────────────────────────────────
        'plus' =>
        '<line x1="12" y1="5" x2="12" y2="19"/>'
            . '<line x1="5" y1="12" x2="19" y2="12"/>',

        'edit' =>
        '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>'
            . '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',

        'delete' =>
        '<polyline points="3 6 5 6 21 6"/>'
            . '<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>'
            . '<path d="M10 11v6"/>'
            . '<path d="M14 11v6"/>'
            . '<path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>',

        'eye' =>
        '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>'
            . '<circle cx="12" cy="12" r="3"/>',

        'download' =>
        '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>'
            . '<polyline points="7 10 12 15 17 10"/>'
            . '<line x1="12" y1="15" x2="12" y2="3"/>',

        'print' =>
        '<polyline points="6 9 6 2 18 2 18 9"/>'
            . '<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>'
            . '<rect x="6" y="14" width="12" height="8"/>',

        'filter' =>
        '<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>',

        'export' =>
        '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>'
            . '<polyline points="17 8 12 3 7 8"/>'
            . '<line x1="12" y1="3" x2="12" y2="15"/>',

        // ── Status / Feedback ────────────────────────────────
        'check' =>
        '<polyline points="20 6 9 17 4 12"/>',

        'x' =>
        '<line x1="18" y1="6" x2="6" y2="18"/>'
            . '<line x1="6" y1="6" x2="18" y2="18"/>',

        'alert' =>
        '<circle cx="12" cy="12" r="10"/>'
            . '<line x1="12" y1="8" x2="12" y2="12"/>'
            . '<line x1="12" y1="16" x2="12.01" y2="16"/>',

        'info' =>
        '<circle cx="12" cy="12" r="10"/>'
            . '<line x1="12" y1="16" x2="12" y2="12"/>'
            . '<line x1="12" y1="8" x2="12.01" y2="8"/>',

        // ── Data / Content ───────────────────────────────────
        'clock' =>
        '<circle cx="12" cy="12" r="10"/>'
            . '<polyline points="12 6 12 12 16 14"/>',

        'location' =>
        '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>'
            . '<circle cx="12" cy="10" r="3"/>',

        'id-card' =>
        '<rect x="2" y="5" width="20" height="14" rx="2"/>'
            . '<circle cx="8" cy="12" r="2"/>'
            . '<path d="M14 9h4"/>'
            . '<path d="M14 13h4"/>',

        'calendar' =>
        '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>'
            . '<line x1="16" y1="2" x2="16" y2="6"/>'
            . '<line x1="8" y1="2" x2="8" y2="6"/>'
            . '<line x1="3" y1="10" x2="21" y2="10"/>',

        'photo' =>
        '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>'
            . '<circle cx="8.5" cy="8.5" r="1.5"/>'
            . '<polyline points="21 15 16 10 5 21"/>',

        'chart-bar' =>
        '<line x1="18" y1="20" x2="18" y2="10"/>'
            . '<line x1="12" y1="20" x2="12" y2="4"/>'
            . '<line x1="6" y1="20" x2="6" y2="14"/>'
            . '<line x1="2" y1="20" x2="22" y2="20"/>',

        'tag' =>
        '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>'
            . '<line x1="7" y1="7" x2="7.01" y2="7"/>',

        'link' =>
        '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>'
            . '<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',

        'save' =>
        '<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>'
            . '<polyline points="17 21 17 13 7 13 7 21"/>'
            . '<polyline points="7 3 7 8 15 8"/>',

        'back' =>
        '<line x1="19" y1="12" x2="5" y2="12"/>'
            . '<polyline points="12 19 5 12 12 5"/>',

        'key' =>
        '<path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>',

        'refresh' =>
        '<polyline points="23 4 23 10 17 10"/>'
            . '<path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>',

    ];

    if (! isset($icons[$name])) {
        return '<!-- svg_icon: unknown icon "' . htmlspecialchars($name) . '" -->';
    }

    return "<svg {$attrs}>{$icons[$name]}</svg>";
}
