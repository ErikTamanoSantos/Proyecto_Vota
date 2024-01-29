let votes = []

let graphType= "bar"
Chart.defaults.color = "#effbff";
Chart.defaults.font.family = "'Raleway', sans-serif;"

$(function() {
    $("#barChartButton").click(function() {
      graphType = "bar";
      $(this).prop("disabled", true)
      $("#pieChartButton").prop("disabled", false)
      Chart.getChart("graph").destroy()
      createGraph();
    })
    $("#pieChartButton").click(function() {
      graphType = "pie";
      $(this).prop("disabled", true)
      $("#barChartButton").prop("disabled", false)
      Chart.getChart("graph").destroy()
      createGraph();
    })
})

function getVotes(votesFromPost) {
  votes = votesFromPost
  createGraph()
}

function createGraph() {
    
      new Chart(
        document.getElementById('graph'),
        {
          type: graphType,
          data: {
            labels: votes.map(row => row.answer),
            datasets: [
              {
                label: 'Votos',
                data: votes.map(row => row.count)
              }
            ]
          },
          options: {
            responsive: true,
            mantainAspectRatio: true
          }
        }
      );
}