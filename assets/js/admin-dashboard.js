document.addEventListener('DOMContentLoaded', function () {
  if (typeof Chart === 'undefined' || !window.__dashboardData) return;

  Chart.defaults.color = '#97a89c';
  Chart.defaults.font.family = "'Plus Jakarta Sans', 'Segoe UI', sans-serif";

  var viewsEl = document.getElementById('viewsChart');
  if (viewsEl) {
    var vd = window.__dashboardData.views;
    var ctx = viewsEl.getContext('2d');
    var gradient = ctx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(34,197,94,.9)');
    gradient.addColorStop(1, 'rgba(45,212,191,.5)');
    new Chart(viewsEl, {
      type: 'bar',
      data: {
        labels: vd.labels,
        datasets: [{ label: 'Views', data: vd.data, backgroundColor: gradient, borderRadius: 8, maxBarThickness: 38 }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#101512', borderColor: '#232b25', borderWidth: 1, padding: 10 } },
        scales: {
          x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true } },
          y: { beginAtZero: true, grid: { color: '#171d19' }, ticks: { precision: 0 } }
        }
      }
    });
  }

  var catEl = document.getElementById('categoryChart');
  if (catEl) {
    var cd = window.__dashboardData.categories;
    new Chart(catEl, {
      type: 'doughnut',
      data: {
        labels: cd.labels,
        datasets: [{
          data: cd.data,
          backgroundColor: ['#22c55e', '#2dd4bf', '#a3e635', '#16a34a', '#5eead4', '#84cc16'],
          borderColor: '#101512', borderWidth: 3, hoverOffset: 6
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false, cutout: '68%',
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14, usePointStyle: true, pointStyle: 'circle' } },
          tooltip: { backgroundColor: '#101512', borderColor: '#232b25', borderWidth: 1, padding: 10 }
        }
      }
    });
  }
});
