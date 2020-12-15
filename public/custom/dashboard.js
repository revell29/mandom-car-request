window.chartColors = {
  red: "rgb(255, 99, 132)",
  orange: "rgb(255, 159, 64)",
  yellow: "rgb(255, 205, 86)",
  green: "rgb(75, 192, 192)",
  blue: "rgb(54, 162, 235)",
  purple: "rgb(153, 102, 255)",
  grey: "rgb(201, 203, 207)"
};

function chartDashboard(labels, data) {
  var ctx = document.getElementById("chartJS");
  var myChart = new Chart(ctx, {
      type: "line",
      data: {
          labels: labels,
          datasets: [
              {
                  label: "Driver Internal",
                  borderColor: window.chartColors.red,
                  borderWidth: 3,
                  backgroundColor: window.chartColors.red,
                  data: data.internal,
                  fill: false
              },
              {
                label: "Driver Eksternal",
                borderColor: window.chartColors.blue,
                borderWidth: 3,
                backgroundColor: window.chartColors.blue,
                data: data.eksternal,
                fill: false
            }
          ]
      },
      options: {
          responsive: true,
          title: {
              display: true,
              text: ""
          },
          tooltips: {
              mode: "index",
              intersect: false
          },
          hover: {
              mode: "nearest",
              intersect: true
          },
          scales: {
              xAxes: [
                  {
                      display: true,
                      scaleLabel: {
                          display: true,
                          labelString: "Bulan"
                      }
                  }
              ],
              yAxes: [
                  {
                      display: true,
                      scaleLabel: {
                          display: true,
                          labelString: "Value"
                      },
                      ticks: {
                          beginAtZero: true,
                          userCallback: function(label, index, labels) {
                              // when the floored value is the same as the value we have a whole number
                              if (Math.floor(label) === label) {
                                  return label;
                              }
                          }
                      }
                  }
              ]
          },
          maintainAspectRatio: false

      }
  });
}

fetch("/backend/dashboard/chart")
  .then(res => res.json())
  .then(res => {
      chartDashboard(res.label, res.data);
  });