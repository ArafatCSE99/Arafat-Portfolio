document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('qrGenBtn');
  var input = document.getElementById('qrText');
  var output = document.getElementById('qrOutput');
  var downloadLink = document.getElementById('qrDownload');

  function generate() {
    var text = input.value.trim();
    output.innerHTML = '';
    downloadLink.style.display = 'none';
    if (!text) return;

    try {
      var qr = qrcode(0, 'M');
      qr.addData(text);
      qr.make();
      var dataUrl = qr.createDataURL(8, 8);
      var img = document.createElement('img');
      img.src = dataUrl;
      img.alt = 'QR code for ' + text;
      img.style.display = 'block';
      output.appendChild(img);
      downloadLink.href = dataUrl;
      downloadLink.style.display = 'block';
    } catch (err) {
      output.innerHTML = '<p style="color:var(--danger);padding:20px;">Could not generate QR code — text may be too long.</p>';
    }
  }

  btn.addEventListener('click', generate);
  generate();
});
