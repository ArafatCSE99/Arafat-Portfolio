document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('cpInput');
  var swatch = document.getElementById('cpSwatch');
  var hexOut = document.getElementById('cpHex');
  var rgbOut = document.getElementById('cpRgb');
  var hslOut = document.getElementById('cpHsl');

  function hexToRgb(hex) {
    var r = parseInt(hex.slice(1, 3), 16);
    var g = parseInt(hex.slice(3, 5), 16);
    var b = parseInt(hex.slice(5, 7), 16);
    return { r: r, g: g, b: b };
  }

  function rgbToHsl(r, g, b) {
    r /= 255; g /= 255; b /= 255;
    var max = Math.max(r, g, b), min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;

    if (max === min) {
      h = s = 0;
    } else {
      var d = max - min;
      s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
      switch (max) {
        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
        case g: h = (b - r) / d + 2; break;
        default: h = (r - g) / d + 4;
      }
      h /= 6;
    }
    return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
  }

  function update() {
    var hex = input.value;
    var rgb = hexToRgb(hex);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);

    swatch.style.background = hex;
    hexOut.value = hex.toUpperCase();
    rgbOut.value = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
    hslOut.value = 'hsl(' + hsl.h + ', ' + hsl.s + '%, ' + hsl.l + '%)';
  }

  input.addEventListener('input', update);
  update();

  document.querySelectorAll('.copy-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetEl = document.getElementById(btn.dataset.target);
      navigator.clipboard.writeText(targetEl.value);
      var orig = btn.innerHTML;
      btn.textContent = 'Copied!';
      setTimeout(function () { btn.innerHTML = orig; }, 1200);
    });
  });
});
