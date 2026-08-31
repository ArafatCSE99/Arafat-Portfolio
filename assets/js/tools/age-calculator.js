document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('calcBtn');
  var dobInput = document.getElementById('dob');
  var result = document.getElementById('result');
  var grid = document.getElementById('resultGrid');

  btn.addEventListener('click', function () {
    if (!dobInput.value) return;
    var dob = new Date(dobInput.value);
    var today = new Date();
    if (dob > today) {
      result.style.display = 'block';
      grid.style.display = 'none';
      result.textContent = 'Date of birth cannot be in the future.';
      return;
    }

    var years = today.getFullYear() - dob.getFullYear();
    var months = today.getMonth() - dob.getMonth();
    var days = today.getDate() - dob.getDate();

    if (days < 0) {
      months -= 1;
      var prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
      days += prevMonth.getDate();
    }
    if (months < 0) {
      years -= 1;
      months += 12;
    }

    result.style.display = 'block';
    grid.style.display = 'grid';
    result.textContent = years + ' years, ' + months + ' months, ' + days + ' days old';
    document.getElementById('rYears').textContent = years;
    document.getElementById('rMonths').textContent = months;
    document.getElementById('rDays').textContent = days;
  });
});
