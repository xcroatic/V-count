(function () {
  function init() {
    ctx = document.getElementById('myChart').getContext('2d');
    getData();
  }

  function getData() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "data.json");

    xhr.onreadystatechange = function () {
      if (this.readyState === XMLHttpRequest.DONE) {
        const data = JSON.parse(this.responseText);
        const enter = [];
        const exit = [];
        const days = [];
        for (const store of data.traffic) {
          enter.push(store.in);
          exit.push(store.out);
          days.push(store.timeformatted);

        }
        createChart(enter, exit, days);
      }
    };

    xhr.send();
  }

  function createChart(enter, exit, days) {
    if (!days?.length){
      days = "There is no data for this time frame"
    }
    myChart = new Chart(ctx, {
      count: 15,
      type: 'bar',
      data: {
        labels: days,
        datasets: [{
          label: 'In',
          data: enter,
          backgroundColor: [
            'rgba(54, 162, 235, 0.2)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 1
        },
        {
          label: 'Out',
          data: exit,
          backgroundColor: [
            'rgba(245, 67, 32, 0.2)'
          ],
          borderColor: [
            'rgba(255, 0, 0, 1)'
          ],
          borderWidth: 1
        }
        ]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            stacked: true,
          },
        }
      }
    });
  }

  window.onload = function () {
    init();
  };
})();