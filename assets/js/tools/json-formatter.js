document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('jsonInput');
  var output = document.getElementById('jsonOutput');
  var alertBox = document.getElementById('jsonAlert');
  var formatBtn = document.getElementById('jsonFormatBtn');
  var minifyBtn = document.getElementById('jsonMinifyBtn');
  var clearBtn = document.getElementById('jsonClearBtn');

  function showAlert(type, message) {
    alertBox.innerHTML = '<div class="alert alert-' + type + '" style="margin-top:18px;">' + message + '</div>';
  }

  function format(minify) {
    alertBox.innerHTML = '';
    var text = input.value.trim();
    if (!text) {
      output.style.display = 'none';
      return;
    }
    try {
      var parsed = JSON.parse(text);
      output.textContent = minify ? JSON.stringify(parsed) : JSON.stringify(parsed, null, 2);
      output.style.display = 'block';
      showAlert('success', 'Valid JSON ✓');
    } catch (err) {
      output.style.display = 'none';
      showAlert('error', 'Invalid JSON — ' + err.message);
    }
  }

  formatBtn.addEventListener('click', function () { format(false); });
  minifyBtn.addEventListener('click', function () { format(true); });
  clearBtn.addEventListener('click', function () {
    input.value = '';
    output.style.display = 'none';
    alertBox.innerHTML = '';
  });
});
