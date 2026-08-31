document.addEventListener('DOMContentLoaded', function () {
  var UNITS = {
    length: {
      meter: 1,
      kilometer: 1000,
      centimeter: 0.01,
      millimeter: 0.001,
      mile: 1609.344,
      yard: 0.9144,
      foot: 0.3048,
      inch: 0.0254
    },
    weight: {
      kilogram: 1,
      gram: 0.001,
      milligram: 0.000001,
      pound: 0.45359237,
      ounce: 0.028349523125,
      ton: 1000
    },
    temperature: { celsius: 1, fahrenheit: 1, kelvin: 1 }
  };

  var categorySelect = document.getElementById('ucCategory');
  var fromSelect = document.getElementById('ucFrom');
  var toSelect = document.getElementById('ucTo');
  var valueInput = document.getElementById('ucValue');
  var result = document.getElementById('ucResult');

  function populateUnits() {
    var cat = categorySelect.value;
    var units = Object.keys(UNITS[cat]);
    fromSelect.innerHTML = units.map(function (u) { return '<option value="' + u + '">' + capitalize(u) + '</option>'; }).join('');
    toSelect.innerHTML = fromSelect.innerHTML;
    toSelect.selectedIndex = units.length > 1 ? 1 : 0;
    convert();
  }

  function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

  function convertTemperature(value, from, to) {
    var celsius;
    if (from === 'celsius') celsius = value;
    else if (from === 'fahrenheit') celsius = (value - 32) * 5 / 9;
    else celsius = value - 273.15;

    if (to === 'celsius') return celsius;
    if (to === 'fahrenheit') return celsius * 9 / 5 + 32;
    return celsius + 273.15;
  }

  function convert() {
    var cat = categorySelect.value;
    var from = fromSelect.value;
    var to = toSelect.value;
    var value = parseFloat(valueInput.value);

    if (isNaN(value)) {
      result.textContent = 'Enter a value';
      return;
    }

    var output;
    if (cat === 'temperature') {
      output = convertTemperature(value, from, to);
    } else {
      var base = value * UNITS[cat][from];
      output = base / UNITS[cat][to];
    }

    result.textContent = value + ' ' + capitalize(from) + ' = ' + round(output) + ' ' + capitalize(to);
  }

  function round(n) {
    return Math.round(n * 100000) / 100000;
  }

  categorySelect.addEventListener('change', populateUnits);
  [fromSelect, toSelect, valueInput].forEach(function (el) {
    el.addEventListener('input', convert);
    el.addEventListener('change', convert);
  });

  populateUnits();
});
