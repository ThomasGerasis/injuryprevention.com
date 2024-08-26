import Chart from 'chart.js/auto';
import * as d3 from 'd3';

const siteUrl = window.location.origin;
const ajaxUrl = siteUrl + '/ajaxFunctions/';
const isMobile = window.innerWidth <= 768; // Check if the device is mobile (width <= 768px) indexAxis based on device type

export function playerMovementChart(playerMovementData) 
{
    const data = {
        labels: ['', '', '', '', '', '', '', '', '', '', '', ''],
        datasets: [
            {
                label: 'Percentage',
                data : playerMovementData,
                fill: true,
                backgroundColor: 'rgb(241, 98, 58)',
                borderColor: 'rgba(0, 0, 0, 0)',
                pointStyle: false
            }
        ]
    };

    // Radar Chart Options
    let options = {
        plugins: {
            filler: {
                propagate: true
            },
            legend: {
                display: false // Hide dataset labels
            }
        },
        elements: {
            line: {
                fill: true,
                pointStyle: false,
                backgroundColor: 'rgba(241, 98, 58, 0.2)',
                spanGaps : true,
                tension : 0.1,
            }
        },
        scales: {
            r: {
                // suggestedMin: 0,
                // suggestedMax: 100,
                grid: {
                    circular: true,
                    // color: "transparent" // Transparent color for the grid lines
                },
                ticks: {
                    // beginAtZero: true,
                    display: false // Hide scale labels
                },
                angleLines: {
                    // color: "transparent",
                    // color: "rgba(255, 255, 255, 1)" // Color of the angle lines
                    color: "rgba(236, 236, 236, 0.39)" // Color of the angle lines
                }
            }
        }
    };

    let ctx = document.getElementById("radarChart").getContext("2d");

    const testPlugin = {
        id: 'custom_canvas_background_color',
        beforeDraw: (chart, args, options) => {
            let {ctx} = chart;
            ctx.save();
            let chartArea = chart.chartArea;
            // Ensure the chart area is defined
            if (!chartArea) return;
            // Calculate the center of the chart
            let centerX = (chartArea.left + chartArea.right) / 2;
            let centerY = (chartArea.top + chartArea.bottom) / 2;

            // Calculate the radius of the chart
            let radius = Math.min((chartArea.right - chartArea.left) / 2, (chartArea.bottom - chartArea.top) / 2);

            // Create the gradient background
            let gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, radius);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 1)'); // Start color (white)
            gradient.addColorStop(0.25, 'rgba(178, 177, 181,0.8)'); // Red color at 25% of the radius
            gradient.addColorStop(0.5, 'rgba(161, 159, 164,0.7)'); // Green color at 50% of the radius
            gradient.addColorStop(0.75, 'rgba(140, 138, 144,0.8)'); // Blue color at 75% of the radius
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0)'); // End color (transparent)
            // Fill the chart area with the gradient background
            ctx.fillStyle = gradient;
            ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
            ctx.restore();
        },
        defaults: {
            color: 'lightGreen'
        }
    }

    const colorCircle = {
        id: 'color_circle',
        beforeDraw: (chart, args, options) => {
            let {ctx} = chart;
            ctx.save();
            let chartWidthHeight = window.innerWidth < 978 ? 200 : 300;
            let centerX = chartWidthHeight / 2;
            let centerY = chartWidthHeight / 2;
            let radius = (chartWidthHeight - 20) / 2; // Adjusted to fit within canvas
            let numSections = 15;
            let padding = window.innerWidth < 978 ? 0 : 4; // Padding for the stroke
            let sectionAngle = (2 * Math.PI) / numSections;
            // let space = 1; // space between sections
            // Draw the outline
            let colors = [
                '#dae020',
                '#e7ed19',
                '#fcf062',
                '#f9dd60',
                '#f5c05c',
                '#f3b159',
                '#ef9454',
                '#e96a41',
                '#dd4932',
                '#cb422d',
                '#b41f29',
                '#7aaa35',
                '#84b939',
                '#8ec63e',
                '#c0d72f'
            ];
            // Example colors
            for (let i = 0; i < numSections; i++) {
                let startAngle = i * sectionAngle;
                let endAngle = (i + 1) * sectionAngle;
                ctx.beginPath();
                ctx.strokeStyle = colors[i];
                ctx.arc(centerX, centerY, radius + padding, startAngle, endAngle);
                ctx.lineWidth = 12;
                ctx.stroke();
            }
            ctx.restore();
        }
    }

    let radarChart = new Chart(
        ctx,
        {
            type: 'radar',
            data: data,
            options: options,
            plugins :[testPlugin,colorCircle]
        }
    );

}

//Chartjs  Risk Chart

// export function riskChart(teamRiskData) 
// {
//     let teamRiskPercentage = teamRiskData['teamRiskPercentage'];

//     let dataSetOptions = {
//         label: '',
//         barPercentage: 1.2,
//         categoryPercentage: 1.2,
//         barThickness: 10,
//     }

//     const data = {
//         labels: ['Low Risk', '', '', '', 'High Risk'],
//         datasets: [
//             { ...dataSetOptions, data: [teamRiskPercentage[0], null, null, null, null], backgroundColor: '#00fb00' },
//             { ...dataSetOptions, data: [teamRiskPercentage[1], null, null, null, null], backgroundColor: '#fae700' },
//             { ...dataSetOptions, data: [null, teamRiskPercentage[2], null, null, null], backgroundColor: '#00fb00' },
//             { ...dataSetOptions, data: [null, teamRiskPercentage[3], null, null, null], backgroundColor: '#fae700' },
//             { ...dataSetOptions, data: [null, null, teamRiskPercentage[4], null, null], backgroundColor: '#00fb00' },
//             { ...dataSetOptions, data: [null, null, teamRiskPercentage[5], null, null], backgroundColor: '#fae700' },
//             { ...dataSetOptions, data: [null, null, teamRiskPercentage[6], null, null], backgroundColor: '#ff7a00' },
//             { ...dataSetOptions, data: [null, null, teamRiskPercentage[7], null, null], backgroundColor: '#ff0000' },
//             { ...dataSetOptions, data: [null, null, null, teamRiskPercentage[8], null], backgroundColor: '#00fb00' },
//             { ...dataSetOptions, data: [null, null, null, teamRiskPercentage[9], null], backgroundColor: '#fae700' },
//             { ...dataSetOptions, data: [null, null, null, teamRiskPercentage[10], null], backgroundColor: '#ff0000' },
//             { ...dataSetOptions, data: [null, null, null, null, teamRiskPercentage[11]], backgroundColor: '#ff0000' },
//         ]
//     };

//     const options = {
//         responsive: true,
//         onClick: (event, elements, chart) => 
//         {
//             const x = event.x;
//             const xScale = chart.scales.x;
//             const labelIndex = xScale.getValueForPixel(x);
            
//             if (labelIndex >= 0) {
//                 // fetch(ajaxUrl + 'buildPlayerPercentage', {
//                 //     method: "POST",
//                 //     headers: {
//                 //         'Accept': 'application/json',
//                 //         'Content-type': 'application/json',
//                 //         "X-Requested-With": "XMLHttpRequest"
//                 //     },
//                 //     body: JSON.stringify({
//                 //         "numberOfAnalysis": numberOfAnalysis,
//                 //         "risk": datasetIndex,
//                 //         "playersRiskPercentages": playersRiskPercentages,
//                 //     })
//                 // })
//                 // .then(
//                 //     response => response.json()
//                 // )
//                 // .then(data => {
//                 //     let jsonData = JSON.parse(data.html);
//                 //     playerPercentage.innerHTML = jsonData;
//                 // })
//                 // .catch(function (error) {
//                 //     console.log(error);
//                 // });
    
//             }
//         },
//         plugins: {
//             legend: {
//                 display: false // Hide dataset labels
//             },
           
//         },
//         scales: {
//             x: {
//                 stacked: false,
//                 grid: {
//                     display: false,
//                     color: "rgba(255, 255, 255, 1)",
//                 },
//                 ticks: {
//                     color: "rgba(255, 255, 255, 1)", // Color of the angle lines
//                 }
//             },
//             y: {
//                 stacked: false,
//                 grid: {
//                     display: true,
//                     color: "rgba(255, 255, 255, 1)",
//                 },
//                 ticks: {
//                     color: "rgba(255, 255, 255, 1)", // Color of the angle lines
//                 },
//                 min: 0, // Minimum value of y-axis
//                 max: 100, // Maximum value of y-axis
//             }
//         },

//     };
    

//     const areaBackground = {
//         id: 'background',
//         beforeDraw: (chart) => {
//             const ctx = chart.ctx;
//             ctx.save();

//             const margin = 10; // Margin between each label area
//             const totalWidth = chart.scales.x.width;
//             const labelCount = chart.scales.x.ticks.length;
//             const groupWidth = totalWidth / labelCount;
//             const rectWidth = groupWidth - margin; // Rectangle width minus margin

//             // Iterate over each label and draw a rectangle behind it
//             chart.scales.x.ticks.forEach((tick, index) => {
//                 // Calculate the x-coordinate for the rectangle
//                 const x = chart.scales.x.getPixelForValue(tick.value) - rectWidth / 2;
//                 // Set the background color for the label areas
//                 ctx.fillStyle = 'rgba(154, 149, 151, 0.5)';
            
//                 // Draw the rectangle with margin
//                 ctx.fillRect(x, chart.chartArea.top, rectWidth, chart.chartArea.bottom - chart.chartArea.top);
//             });

//             ctx.restore();
//         }
//     }

//     let ctx = document.getElementById("riskChart").getContext("2d");
    
//     const myChart = new Chart(ctx, {
//         type: 'bar',
//         data: data,
//         options: options,
//         plugins: [areaBackground]
//     });

// }


//D3 js risk chart
export function riskChart(teamData) {
    const margin = { top: 20, right: 30, bottom: 40, left: 40 };
    const width = isMobile ? 360 - margin.left - margin.right : 700 - margin.left - margin.right;
    const height = isMobile ? 240 - margin.top - margin.bottom : 280 - margin.top - margin.bottom;
  
    let data  = teamData['teamRiskPercentage'];

    let playersRisks = teamData['playersRiskPercentages'];
    let teamRiskPercentage = [
        10,
        null,
        30,
        50,
        40,
        60,
        30,
        70,
        90,
        40,
        50,
        70,
      ];

    const svg = d3
      .select("#riskChart")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .append("g")
      .attr("transform", `translate(${margin.left},${margin.top})`);
  
    const labels = ["0-1", "2-3", "4-7", "8-10", "11"];
    const sectionData = [
        data.slice(0, 1),
        data.slice(2, 4),
        data.slice(4, 8),
        data.slice(8, 11),
        data.slice(11)
    ];
  
    // Define scales
    const x0 = d3.scaleBand().domain(labels).range([0, width]).padding(0.2);
  
    const x1 = d3
      .scaleBand()
      .domain(d3.range(4)) // Maximum of 4 bars per section
      .range([0, x0.bandwidth()])
      .padding(0.1);
  
    const y = d3.scaleLinear().domain([0, 100]).range([height, 0]);
  
    const color = d3
      .scaleOrdinal()
      .domain(d3.range(4))
      .range(["#f1633b", "#e34c22", "#ff7a00", "#e34c22"]);
  
    // Add shaded background areas
    svg
      .selectAll(".background-rect")
      .data(labels)
      .enter()
      .append("rect")
      .attr("class", "background-rect")
      .attr("x", (d) => x0(d))
      .attr("y", 0)
      .attr("width", x0.bandwidth())
      .attr("height", height)
      .attr("fill", "rgba(154, 149, 151, 0.5)");
  
    // Add bars
    svg
      .append("g")
      .selectAll("g")
      .data(sectionData)
      .enter()
      .append("g")
      .attr("transform", (d, i) => `translate(${x0(labels[i])},0)`)
      .selectAll("rect")
      .data((d, i) => d)
      .enter()
      .append("rect")
      .attr("x", (d, i) => x1(i))
      .attr("y", (d) => y(d))
      .attr("width", x1.bandwidth())
      .attr("height", (d) => height - y(d))
      .attr("fill", (d, i) => color(i))
      .attr("class", (d, i) => ("bar"))
      .on("click", function(event, d) {
        handleRiskScalesClick(event, d,playersRisks);
      }) // Add event listener to each bar;
      .on("mouseover", function(event, d) {
        d3.select(this).classed("highlight", true); // Add 'hovered' class on hover
      })
      .on("mouseout", function(event, d) {
        d3.select(this).classed("highlight", false); // Remove 'hovered' class when not hovering
      });

        // Add y-axis with labels
        svg.append("g")
            .call(d3.axisLeft(y));

        // Add "Low Risk" label
        svg.append("text")
            .attr("x", -margin.left / 4)
            .attr("y", height + margin.bottom - 10)
            .attr("class", "label-text text-white")
            .style("text-anchor", "start")
            .text("Low Risk");

        // Add "High Risk" label
        svg.append("text")
            .attr("x", width + margin.right / 4)
            .attr("y", height + margin.bottom - 10)
            .attr("class", "label-text text-white")
            .style("text-anchor", "end")
            .text("High Risk");
            
        const yAxis = d3
        .axisLeft(y)
        .tickSize(-width) // Extend the tick lines across the width of the chart
        .tickPadding(10); // Space between ticks and axis labels
        
        svg.append("g").call(yAxis).selectAll("text").style("fill", "white"); // Set y-axis label color to white
    
        svg
            .selectAll(".tick line") // Select only the tick lines
            .style("stroke", "white") // Set the stroke color to white for the tick lines
            .style("stroke-width", 1) // Optional: Set the width of the tick lines
            .style("stroke-opacity", 0.6); // Optional: Set the width of the tick lines
}

function handleRiskScalesClick(event, d,playersRisks) 
{
    console.log(d);
    console.log(playersRisks);
    fetch(ajaxUrl + 'buildPlayerPercentage', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "numberOfAnalysis": 0,
            "risk": d,
            "playersRiskPercentages": playersRisks,
        })
    })
    .then(
        response => response.json()
    )
    .then(data => {
        let jsonData = JSON.parse(data.html);
        playerPercentage.innerHTML = jsonData;
    })
    .catch(function (error) {
        console.log(error);
    });
}

export function varianceChart() {
  
    const data = {
        labels: [
            'kadeem_allen',
            'kadeem_allen',
            'jabari_bird',
            'Image 4',
            'Image 5',
            'jabari_bird',
            'Image 7',
            'Image 8',
            'Image 9',
            'Image 10',
        ],
        datasets: [
            {
                label: 'Value',
                data: [90, 80, 70, 60, 50, 40, 30, 20, 15, 10],
                fill: true,
                backgroundColor: 'rgb(241, 98, 58)',
            }
        ]
    };

    if (isMobile) {
         // Reverse the data arrays
        data.labels.reverse();
        data.datasets.forEach(dataset => {
            dataset.data.reverse();
        });
    }

    // Radar Chart Options
    let options = {
        indexAxis: isMobile ? 'y' : 'x', // Set indexAxis based on device type
        plugins: {
            legend: {
                display: false // Hide dataset labels
            }
        },
        layout: {
            padding: {
                left: isMobile ? 35 : 0, // Add left padding for mobile, adjust as needed
                right: 0, // Optional: Add right padding if needed
                top: 0, // Optional: Add top padding if needed
                bottom: 0 // Optional: Add bottom padding if needed
            }
        },
        scales: {
            y: {
                beginAtZero: !isMobile,
                padding: isMobile ? 55 : 0,
                grid: {
                    color: "rgba(255, 255, 255, 1)",
                    display: !isMobile
                    // Color of the angle lines
                },
                ticks: {
                    padding: 45,
                    color: "rgba(255, 255, 255, 1)", // Color of the angle lines
                    display: false // Hide y-axis labels
                }
            },
            x: {
                beginAtZero: isMobile,
                ticks: {
                    padding: isMobile ? 0 : 45, // Add padding at the bottom for x-axis labels on mobile
                    color: "rgba(255, 255, 255, 1)" // Color of the angle lines
                    // callback: ((value,index,values) => {
                    //     return '';
                    // })
                },
                grid: {
                    color: "rgba(255, 255, 255, 1)",
                    display: isMobile
                },
            },
        },
        elements: {
            line: {
                borderWidth: 3
            }
        },

    };


    const playerImages = {
        id: 'playerImage',
        afterDatasetDraw: (chart, args, options) => {
            const {ctx,data,chartArea:{left,bottom},scales : {x,y}} = chart;
            ctx.save();
            data.labels.forEach((playerName,index) =>{
                const label = new Image();
                label.src = getPlayerImageFromName(playerName);
                if (isMobile) {
                    // For mobile, draw images on the y-axis
                    ctx.drawImage(label,left-35,y.getPixelForValue(index) - 12,25,25);
                } else {
                    ctx.drawImage(label,x.getPixelForValue(index) - 25,x.top,50,40);
                }
            })
            // ctx.restore();
        },
        defaults: {
            color: 'lightGreen'
        }
    }

    let barChartIdentifier = isMobile ? 'barChartMobile' : 'barChart';

    let ctx = document.getElementById(barChartIdentifier).getContext("2d");

    let barChart = new Chart(
        ctx,
        {
            type: 'bar',
            data: data,
            options: options,
            plugins :[playerImages]
        }
    );

}

function getPlayerImageFromName(playerName) {
  return window.location.origin + '/assets/img/players/'+playerName+'.svg'
}

