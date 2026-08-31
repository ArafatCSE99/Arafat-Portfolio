function showOutput(id, text) {
  var el = document.getElementById(id);
  el.style.display = 'block';
  el.textContent = text;
}

function calcP1() {
  var x = parseFloat(document.getElementById('p1x').value);
  var y = parseFloat(document.getElementById('p1y').value);
  if (isNaN(x) || isNaN(y)) return showOutput('p1Result', 'Please enter valid numbers.');
  var result = (x / 100) * y;
  showOutput('p1Result', x + '% of ' + y + ' = ' + round(result));
}

function calcP2() {
  var x = parseFloat(document.getElementById('p2x').value);
  var y = parseFloat(document.getElementById('p2y').value);
  if (isNaN(x) || isNaN(y) || y === 0) return showOutput('p2Result', 'Please enter valid numbers (Y cannot be 0).');
  var result = (x / y) * 100;
  showOutput('p2Result', x + ' is ' + round(result) + '% of ' + y);
}

function calcP3() {
  var x = parseFloat(document.getElementById('p3x').value);
  var y = parseFloat(document.getElementById('p3y').value);
  if (isNaN(x) || isNaN(y) || x === 0) return showOutput('p3Result', 'Please enter valid numbers (X cannot be 0).');
  var result = ((y - x) / Math.abs(x)) * 100;
  var direction = result >= 0 ? 'increase' : 'decrease';
  showOutput('p3Result', round(Math.abs(result)) + '% ' + direction + ' from ' + x + ' to ' + y);
}

function round(n) {
  return Math.round(n * 100) / 100;
}
