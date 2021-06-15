(function () {
  //Initializing some variables for buttons as soon as the script loads
  let enterBtn = document.getElementById('enter'),
    exitBtn = document.getElementById('exit'),
    enterAndExitBtn = document.getElementById('enterAndExit'),
    datepicker = document.getElementById('datepicker'),
    enterBtnPressed = false,
    exitBtnPressed = false,
    enterAndExitBtnPressed = false,
    totalIn = 0,
    totalOut = 0;

  function init() {

    ctx = document.getElementById('myChart').getContext('2d');
    inP = document.getElementById('peopleIn');
    outP = document.getElementById('peopleOut');
    getData();

  }

  //function to get data from the json file and send it to a function that creates a chart
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
        createChart(enter, exit, days, data.traffic);
      }
    };

    xhr.send();
  }
  //function to create the chart
  function createChart(enter, exit, days, traffic) {

    // datepicker.innerHTML = traffic[0].timeformatted + " - " + traffic.slice(-1).pop().timeformatted

    myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: days,
        datasets: [{
          label: 'In',
          data: enter,
          backgroundColor: [
            'rgba(54, 162, 235, 0.6)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 2
        },
        {
          label: 'Out',
          data: exit,
          backgroundColor: [
            'rgba(245, 67, 32, 0.6)'
          ],
          borderColor: [
            'rgba(255, 0, 0, 1)'
          ],
          borderWidth: 2
        }
        ]
      },
      options: {
        responsive: false,
        scales: {
          x: {
            stacked: true,
          },
        },
        plugins: {
          legend: {
            position: 'bottom',
            display: false
          },
          title: {
            display: true,
            text: 'Traffic'
          }
        }
      }
    });
    for (const one of enter) {
      totalIn += one;
    }
    for (const one of exit) {
      totalOut += one;
    }
    inP.innerHTML = "People entered: " + totalIn;
    outP.innerHTML = "People exited: " + totalOut;

  }

  window.onload = function () {
    init();
  };

  //showing only "in" data (how many people entered) and making the in button active
  enterBtn.onclick = function () {
    if (!enterBtnPressed) {
      if (exitBtnPressed) {
        myChart.config.type = 'line'
        showHideIn();
        showHideOut();
      } else {
        myChart.config.type = 'line'
        showHideOut()
      }
      if (exit.classList.contains('active')) {
        exit.classList.remove('active')
      } else if (enterAndExitBtn.classList.contains('active')) {
        enterAndExitBtn.classList.remove('active')
      }
      enter.classList.add('active')
      enterBtnPressed = true;
      exitBtnPressed = false;
      enterAndExitBtnPressed = false;
      inP.innerHTML = "People entered: " + totalIn;
      outP.innerHTML = "";
    } else {
      return
    }
  }
  //showing only "out" data (how many people exited) and making the out button active
  exitBtn.onclick = function () {
    if (!exitBtnPressed) {
      if (enterBtnPressed) {
        myChart.config.type = 'line'
        showHideIn();
        showHideOut();
      } else {
        myChart.config.type = 'line'
        showHideIn()
      }
      if (enter.classList.contains('active')) {
        enter.classList.remove('active')
      } else if (enterAndExitBtn.classList.contains('active')) {
        enterAndExitBtn.classList.remove('active')
      }
      exit.classList.add('active')
      enterBtnPressed = false;
      exitBtnPressed = true;
      enterAndExitBtnPressed = false;
      outP.innerHTML = "People exited: " + totalOut;
      inP.innerHTML = "";
    } else {
      return
    }
  }
  enterAndExitBtn.onclick = function () {
    if (!enterAndExitBtnPressed) {
      if (enterBtnPressed) {
        myChart.config.type = 'bar'
        showHideOut();
      } else if (exitBtnPressed) {
        myChart.config.type = 'bar'
        showHideIn();
      }
      if (enter.classList.contains('active')) {
        enter.classList.remove('active')
      } else if (exit.classList.contains('active')) {
        exit.classList.remove('active')
      }
      enterAndExitBtn.classList.add('active')
      enterBtnPressed = false;
      exitBtnPressed = false;
      enterAndExitBtnPressed = true;
      outP.innerHTML = "People exited: " + totalOut;
      inP.innerHTML = "People entered: " + totalIn;;
    } else {
      return
    }

  }

  function showHideIn() {
    inElements = myChart.data.datasets[0];
    inElements.hidden = !inElements.hidden;
    myChart.update();

  }
  function showHideOut() {
    outElements = myChart.data.datasets[1];
    outElements.hidden = !outElements.hidden;
    myChart.update();
  }
})();

//Functions called on a press of the client stated in the html (onclick(),onsubmit()...)
function dateCheck() {
  const startDate = document.getElementById('start');
  const endDate = document.getElementById('end').value.split('-');

  startDate.setAttribute("max", endDate[0] + "-" + endDate[1] + "-" + (endDate[2] - 1));
}
let compareDatesPressed = false;
function compareDates() {
  const popover = document.getElementsByClassName("popover-content");
  if (!compareDatesPressed) {
    popover[0].innerHTML = `
      <div class="dateform d-flex justify-content-center flex-column align-items-center text-center p-3">
        <form action="index.php" method="POST" onsubmit="dateChanger()">
          <label for="start" class="m-2">Choose a starting date:</label>
          <input data-role="datepicker" data-distance="1" type="date" required name="start" id="start">
          <label for="end" class="m-2">Choose an ending date:</label>
          <input data-role="datepicker" data-distance="1" type="date" required name="end" id="end">
        <div class="d-flex justify-content-center align-items-center mt-2">
          <input type="checkbox" onclick="compareDates()" data-role="checkbox">
          <label for="comparisons">Compare dates</label>
        </div>
          <label for="start" class="m-2">Choose a starting date:</label>
          <input data-role="datepicker" data-distance="1" type="date" required name="start" id="start">
          <label for="end" class="m-2">Choose an ending date:</label>
          <input data-role="datepicker" data-distance="1" type="date" required name="end" id="end" >
          <input type="submit" value="Change Date" class="mt-4">
        </form>
      </div>    
    `
    check.checked = true;
    compareDatesPressed = true;
  } else if (compareDatesPressed) {
    popover[0].innerHTML = `
      <div class="dateform d-flex justify-content-center flex-column align-items-center text-center p-3">
        <form action="index.php" method="POST" onsubmit="dateChanger()">
          <label for="start" class="m-2">Choose a starting date:</label>
          <input data-role="datepicker" data-distance="1" type="date" required name="start" id="start" >
          <label for="end" class="m-2">Choose an ending date:</label>
          <input data-role="datepicker" data-distance="1" type="date" required name="end" id="end" onchange="dateCheck()" ">
          <input type="submit" value="Change Date" class="mt-4">
        </form>
        <div class="d-flex justify-content-center align-items-center mt-2">
          <input type="checkbox" onclick="compareDates()" data-role="checkbox">
          <label for="comparisons">Compare dates</label>
        </div>
      </div>    
    `
    compareDatesPressed = false;
  }
}