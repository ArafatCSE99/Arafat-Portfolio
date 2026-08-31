document.addEventListener('DOMContentLoaded', function () {
  var textarea = document.getElementById('wcText');

  function update() {
    var text = textarea.value;
    var trimmed = text.trim();

    var words = trimmed === '' ? 0 : trimmed.split(/\s+/).length;
    var chars = text.length;
    var sentences = trimmed === '' ? 0 : (trimmed.match(/[.!?]+(?=\s|$)/g) || []).length || (trimmed ? 1 : 0);
    var minutes = Math.max(1, Math.ceil(words / 200));

    document.getElementById('wcWords').textContent = words;
    document.getElementById('wcChars').textContent = chars;
    document.getElementById('wcSentences').textContent = sentences;
    document.getElementById('wcTime').textContent = words === 0 ? '0 min' : minutes + ' min';
  }

  textarea.addEventListener('input', update);
  update();
});
