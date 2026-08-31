document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('bmiBtn');
  var result = document.getElementById('bmiResult');

  btn.addEventListener('click', function () {
    var height = parseFloat(document.getElementById('bmiHeight').value);
    var weight = parseFloat(document.getElementById('bmiWeight').value);
    result.style.display = 'block';

    if (!height || !weight || height <= 0 || weight <= 0) {
      result.textContent = 'Please enter valid height and weight.';
      return;
    }

    var heightM = height / 100;
    var bmi = weight / (heightM * heightM);
    var category;
    if (bmi < 18.5) category = 'Underweight';
    else if (bmi < 25) category = 'Normal weight';
    else if (bmi < 30) category = 'Overweight';
    else category = 'Obese';

    result.textContent = 'BMI: ' + bmi.toFixed(1) + ' — ' + category;
  });
});
