document.addEventListener('DOMContentLoaded', function () {
  var output = document.getElementById('pwOutput');
  var lengthInput = document.getElementById('pwLength');
  var lengthVal = document.getElementById('pwLengthVal');
  var upper = document.getElementById('pwUpper');
  var lower = document.getElementById('pwLower');
  var numbers = document.getElementById('pwNumbers');
  var symbols = document.getElementById('pwSymbols');
  var genBtn = document.getElementById('pwGenBtn');
  var copyBtn = document.getElementById('pwCopy');
  var strengthFill = document.getElementById('pwStrength');
  var strengthLabel = document.getElementById('pwStrengthLabel');

  var CHARSETS = {
    upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    lower: 'abcdefghijklmnopqrstuvwxyz',
    numbers: '0123456789',
    symbols: '!@#$%^&*()-_=+[]{}?'
  };

  function secureRandomInt(max) {
    var array = new Uint32Array(1);
    window.crypto.getRandomValues(array);
    return array[0] % max;
  }

  function generate() {
    var length = parseInt(lengthInput.value, 10);
    var pool = '';
    if (upper.checked) pool += CHARSETS.upper;
    if (lower.checked) pool += CHARSETS.lower;
    if (numbers.checked) pool += CHARSETS.numbers;
    if (symbols.checked) pool += CHARSETS.symbols;

    if (!pool) {
      output.value = '';
      updateStrength('');
      return;
    }

    var password = '';
    for (var i = 0; i < length; i++) {
      password += pool.charAt(secureRandomInt(pool.length));
    }
    output.value = password;
    updateStrength(password);
  }

  function updateStrength(password) {
    var score = 0;
    if (password.length >= 8) score++;
    if (password.length >= 14) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    var levels = [
      { pct: 0, color: 'var(--danger)', label: 'Strength: —' },
      { pct: 20, color: 'var(--danger)', label: 'Strength: Very Weak' },
      { pct: 40, color: '#f59e0b', label: 'Strength: Weak' },
      { pct: 60, color: '#f59e0b', label: 'Strength: Fair' },
      { pct: 80, color: 'var(--success)', label: 'Strength: Strong' },
      { pct: 100, color: 'var(--success)', label: 'Strength: Very Strong' }
    ];
    var level = password === '' ? levels[0] : levels[score];
    strengthFill.style.width = level.pct + '%';
    strengthFill.style.background = level.color;
    strengthLabel.textContent = level.label;
  }

  lengthInput.addEventListener('input', function () {
    lengthVal.textContent = lengthInput.value;
    generate();
  });
  [upper, lower, numbers, symbols].forEach(function (el) {
    el.addEventListener('change', generate);
  });
  genBtn.addEventListener('click', generate);
  copyBtn.addEventListener('click', function () {
    if (!output.value) return;
    navigator.clipboard.writeText(output.value).then(function () {
      copyBtn.textContent = 'Copied!';
      setTimeout(function () { copyBtn.innerHTML = document.querySelector('#pwCopy').dataset.orig || 'Copy'; }, 1500);
    });
  });
  copyBtn.dataset.orig = copyBtn.innerHTML;

  generate();
});
