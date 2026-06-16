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
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" style="display: block;" viewBox="0 0 1880 1912" width="{$s}" height="{$s}" class="{$c}" preserveAspectRatio="none">
<path transform="translate(0,0)" fill="rgb(255,255,255)" d="M 0 0 L 1880 0 L 1880 1912 L 0 1912 L 0 0 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1256.88 363.005 L 1541.56 362.855 C 1541.95 395.205 1542.09 427.556 1541.99 459.908 C 1481.16 461.216 1415 460.082 1353.95 459.929 L 1354.02 672.25 C 1353.99 673.764 1353.81 675.366 1353.68 676.885 C 1350.86 678.699 1302.1 677.928 1295.25 677.925 L 1076.9 677.945 C 1076.34 645.271 1076.28 612.59 1076.72 579.914 C 1136.21 579.95 1197.75 580.662 1257.1 579.582 C 1255.87 508.483 1256.89 434.32 1256.88 363.005 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 504.072 1265.86 C 594.386 1267.68 690.326 1266.05 781.105 1266.01 C 780.681 1298.21 781.344 1330.76 780.964 1363.13 C 720.787 1362.79 660.608 1362.77 600.431 1363.08 C 600.963 1385.24 601.658 1574.44 599.944 1576.74 L 596.25 1577.01 L 316.359 1576.96 C 315.075 1545.85 316.084 1511.54 315.686 1479.87 C 377.805 1480.65 441.849 1479.93 504.06 1479.9 C 502.911 1410.41 503.281 1335.45 504.072 1265.86 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 144.025 916.963 L 240.656 916.958 C 240.074 935.013 240.696 956.332 240.711 974.755 C 240.921 1018.15 240.774 1061.55 240.271 1104.94 L 426.315 1104.94 L 426.424 1275.72 C 426.427 1310.59 427.187 1348.16 426.165 1382.84 C 423.207 1384.93 340.756 1383.95 330.036 1383.93 L 330.059 1203 L 144.057 1203.19 L 144.025 916.963 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1440.73 555.904 L 1537.08 555.911 C 1538.08 615.529 1537.25 677.34 1537.17 737.062 C 1595.61 736.304 1655.2 736.87 1713.73 736.849 C 1712.66 831.772 1713.99 928.103 1713.63 1023.21 C 1682.11 1022.58 1648.68 1023.07 1616.96 1022.99 L 1616.98 834.746 C 1558.44 835.409 1499.22 834.957 1440.63 834.935 C 1441.71 805.002 1440.7 768.072 1440.68 737.685 L 1440.73 555.904 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1261.3 1118.13 C 1293.4 1117.52 1326.69 1117.95 1358.83 1118.13 C 1357.88 1141.08 1358.4 1168.02 1358.38 1191.13 C 1358.1 1227.11 1358.32 1263.09 1359.04 1299.06 L 1535.09 1299.1 L 1535 1583.89 L 1437.96 1583.94 C 1437.16 1562.29 1437.75 1537.18 1437.73 1515.21 C 1437.57 1475.49 1437.63 1435.77 1437.92 1396.05 L 1261.91 1395.95 C 1260.74 1363.11 1261.28 1328.22 1261.3 1295.23 L 1261.3 1118.13 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1214.87 924.884 C 1219.16 925.107 1223.63 924.965 1227.93 924.995 C 1329.83 925.7 1431.92 924.06 1533.8 925.243 C 1532.69 945.498 1533.36 971.936 1533.35 992.731 C 1533.19 1029.16 1533.31 1065.58 1533.73 1102.01 L 1679.96 1102.07 L 1680.08 1199.35 C 1600.21 1197.84 1516.51 1199.02 1436.35 1199.1 C 1435.89 1140.1 1435.81 1081.09 1436.12 1022.09 L 1214.27 1022.04 C 1215.56 990.997 1214.01 956.666 1214.87 924.884 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 323.036 355.716 C 330.981 356.338 346.785 355.925 355.347 355.941 L 419.973 356.031 L 420.095 544.009 L 596.162 544.072 L 596.053 821.934 L 499.062 821.91 L 499.216 640.932 L 322.871 640.794 L 323.036 355.716 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 876.934 1255.92 C 908.288 1256.64 942.425 1255.93 974.004 1255.9 C 973.384 1287.43 973.96 1320.69 973.966 1352.38 L 973.998 1540.82 C 916.82 1542.25 854.497 1541.02 797.03 1540.95 C 796.981 1560.3 798.739 1718.15 795.784 1722.06 L 700.337 1721.92 C 701.053 1712.58 700.538 1696.2 700.546 1686.42 L 700.591 1617.28 C 700.619 1561.43 701.892 1499.41 700.184 1444.06 L 876.877 1443.91 L 876.934 1255.92 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 177.862 741.033 C 259.836 742.046 344.212 741.015 426.385 741.044 C 426.072 800.053 426.216 859.063 426.816 918.07 L 643.006 917.95 C 643.466 948.57 643.968 984.984 643.03 1015.36 C 611.83 1014.09 574.802 1015.03 543.215 1015.03 L 329.904 1014.89 L 330.012 837.851 L 177.923 837.844 C 176.548 807.92 177.449 771.235 177.862 741.033 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1064.1 1482.52 C 1073.14 1483.4 1088.51 1483.05 1097.88 1483.06 L 1154.61 1483.03 L 1342.06 1483.11 L 1342.06 1579 L 1161.1 1578.96 L 1161.24 1693.08 C 1161.26 1712.18 1162.06 1736.89 1160.83 1755.32 C 1158.54 1757.19 1157.57 1756.35 1153.59 1756.13 L 876.109 1755.94 C 876.857 1747.02 876.299 1731.57 876.271 1722.13 C 876.135 1701.13 876.167 1680.12 876.367 1659.12 L 1064.26 1658.98 C 1062.78 1603 1063.77 1538.9 1064.1 1482.52 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1060.64 217.998 L 1157.3 218.06 C 1156.38 248.109 1157.2 282.197 1157.22 312.595 L 1157.25 495.894 C 1099.12 496.735 1039.22 496.003 980.948 496.017 L 981.183 683.931 C 949.642 684.517 915.53 684.118 883.965 683.834 L 883.865 399.125 C 942.269 398.369 1002.21 399.077 1060.74 399.085 L 1060.64 217.998 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 696.022 184.049 L 981.759 184.005 C 980.219 213.598 981.302 250.585 981.239 280.831 L 793.898 280.908 C 792.041 334.783 793.643 398.741 793.92 453.249 C 793.937 455.003 793.838 454.632 793.198 456.393 C 788.223 457.284 771.258 457.049 765.531 457.052 L 711.553 457.047 L 515.146 456.94 C 516.167 425.19 515.055 392.972 515.931 361.04 L 696.275 361.027 L 696.568 250.359 C 696.602 229.212 697.155 204.935 696.022 184.049 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 954.451 969.558 C 961.821 969.119 970.111 969.238 977.562 969.188 C 978.029 1010.84 979.885 1052.29 980.157 1093.98 C 1038.8 1094.32 1099.43 1094.63 1158 1093.86 C 1156.35 1188.08 1158.34 1284.67 1157.7 1379.24 C 1132.66 1379.02 1107.61 1378.94 1082.56 1379.01 L 1060.84 1379.11 C 1059.12 1319.32 1060.55 1251.35 1060.66 1190.97 L 885.228 1191.22 C 883.036 1118.33 883.159 1043.26 880.324 970.804 C 904.683 970.003 929.993 969.957 954.451 969.558 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 700.171 560.611 C 727.446 562.289 768.738 560.953 797.01 560.921 L 796.988 780.941 C 857.095 781.705 918.74 780.985 978.972 780.976 L 979 878.968 C 902.071 880.655 824.229 878.049 747.209 879.062 C 732.125 879.26 715.275 878.255 700.422 879.917 C 698.646 844.805 699.933 800.666 699.931 764.712 L 700.171 560.611 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 707.993 974.121 C 740.351 973.907 772.711 973.935 805.069 974.205 L 804.979 1199.22 C 743.499 1197.78 676.322 1199.06 614.367 1199.06 L 520.129 1199.22 C 520.255 1167.54 519.405 1133.53 520.368 1102.11 L 707.821 1102.05 C 707.397 1097.29 707.618 1092.43 707.701 1087.66 C 708.361 1049.82 707.359 1011.95 707.993 974.121 z"/>
<path transform="translate(0,0)" fill="rgb(21,122,23)" d="M 1052.89 747.828 C 1079.09 748.92 1113.17 747.913 1139.87 747.909 L 1337.79 748.044 L 1337.62 845.951 L 1215.5 845.978 L 1149.81 845.973 L 1149.91 967.978 L 1052.68 967.884 L 1052.45 827.221 C 1052.43 802.736 1051.59 771.885 1052.89 747.828 z"/>
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
