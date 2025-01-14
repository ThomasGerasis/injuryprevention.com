import Chart from 'chart.js/auto';
import * as d3 from 'd3';
import {setUpSliders} from "../customSwiper";

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
                '#fae35f',
                '#f8db5f',
                '#f5c65c',
                '#f2af59',
                '#f09c54',
                '#ea7441',
                '#e05c33',
                '#d7502e',
                '#c7432a',
                '#c82c23',
                '#c60d1e',
                '#7ef849',
                '#8de743',
                '#adcb30',
                '#c0d630'
            ];
            // Example colors
            for (let i = 0; i < numSections; i++) {
                let startAngle = i * sectionAngle - 0.02; // Overlap by a small angle
                let endAngle = (i + 1) * sectionAngle + 0.02;
                ctx.beginPath();
                ctx.strokeStyle = colors[i];
                ctx.arc(centerX, centerY, radius + padding, startAngle, endAngle);
                ctx.lineWidth = 12; // Keep the line width
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
    let dataArray = Object.values(data); // Convert object to array

    let teamRiskPercentageCount = teamData['teamRiskCount'];
    let playersRisks = teamData['playersRiskPercentages'];

    const svg = d3
      .select("#riskChart")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .append("g")
      .attr("transform", `translate(${margin.left},${margin.top})`);

      //1 negligable
      //2-3 low
      //4-6 medium
      //7-8 high
      //9-11 very high

    const labels = ["Negligible", "Low Risk", "Medium Risk", "High Risk", "Very High Risk"];
    const sectionData = [
         [dataArray[0] + dataArray[1]],               // Negligible (Risk 1)
         dataArray.slice(2, 4),                       // Low Risk (Risk 2-3)
         dataArray.slice(4, 7),                       // Medium Risk (Risk 4-6)
         dataArray.slice(7, 9),                       // High Risk (Risk 7-8)
         dataArray.slice(9, 12)                       // Very High Risk (Risk 9-11)
    ];
  
    // Define scales
    const x0 = d3.scaleBand().domain(labels).range([0, width]).padding(0.2);
    const y = d3.scaleLinear().domain([0, 100]).range([height, 0]);

    const maxBarWidth = 60; // Define the maximum width for the bars

    const color = d3
        .scaleOrdinal()
        .domain(d3.range(4))
        .range(["#f1633b", "#e34c22", "#ff7a00", "#e34c22"]);

    // const maxBackgroundWidth = 40; // Define the maximum width for the background rectangles

    // Add shaded background areas
    svg
    .selectAll(".background-rect")
    .data(labels)
    .enter()
    .append("rect")
    .attr("x", (d) => x0(d) + (x0.bandwidth() - Math.min(x0.bandwidth(), maxBarWidth)) / 2) // Center the rectangle
    .attr("y", 0)
    .attr("width", (d) => Math.min(x0.bandwidth(), maxBarWidth)) // Apply the max width constraint
    .attr("height", height)
    .attr("fill", "rgba(150, 146, 149, 1)");
  
    let globalIndex = 1;
    let firstBar = true; // Flag to ensure you only trigger it for the first bar
    // Add bars
    svg
    .append("g")
    .selectAll("g")
    .data(sectionData)
    .enter()
    .append("g")
    .attr("transform", (d, i) => {
        const sectionWidth = Math.min(x0.bandwidth(), maxBarWidth); // Constrained section width
        const offset = x0(labels[i]) + (x0.bandwidth() - sectionWidth) / 2; // Center the section
        return `translate(${offset},0)`;
      })
    .each(function (sectionData, sectionIndex) {
        // Define x1 scale dynamically for this section
        const sectionWidth = Math.min(x0.bandwidth(), maxBarWidth);
        const x1 = d3.scaleBand()
          .domain(d3.range(sectionData.length)) // Number of bars
          .range([0, sectionWidth]) // Adjust range to constrained width

        d3.select(this)
            .selectAll("rect")
            .data(sectionData.map((value, index) => ({ value, index }))) // Map data to include index
            .enter()
            .append("rect")
            .attr("x", (d) => x1(d.index)) // Position bars using adjusted scale
            .attr("y", (d) => y(d.value))
            .attr("width", Math.min(x1.bandwidth(), sectionWidth / sectionData.length - x1.paddingInner() * sectionWidth)) // Ensure bars fit
            .attr("height", (d) => height - y(d.value))
            .attr("fill", (d) => color(d.index))
            .attr("class", "bar")
            .on("mouseover", function () {
                d3.select(this).classed("highlight", true);
            })
            .on("mouseout", function () {
                d3.select(this).classed("highlight", false);
            })
            .each(function (d) {
                const currentIndex = globalIndex;
                globalIndex++;
                d3.select(this).on("click", function () {
                    handleRiskScalesClick(currentIndex, playersRisks, teamRiskPercentageCount);
                });

                 // Automatically trigger the click handler for the first bar
                if (firstBar) {
                    handleRiskScalesClick(currentIndex, playersRisks, teamRiskPercentageCount);
                    firstBar = false; // Ensure it only triggers once
                }
                
            });
    });
    // Add y-axis with labels
    svg.append("g").call(d3.axisLeft(y));

    svg
    .selectAll(".section-label")
    .data(labels)
    .enter()
    .append("text")
    .attr("class", "section-label")
    .attr("x", (d) => x0(d) + x0.bandwidth() / 2) // Center the label within each section
    .attr("y", height + 20) // Position below the bars
    .attr("text-anchor", "middle")
    .attr("fill", "white") // Set text color
    .text((d) => d);

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

export function handleRiskScalesClick(d,playersRisks,teamRiskPercentageCount) 
{
    fetch(ajaxUrl + 'buildPlayerPercentage', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "numberOfAnalysis": teamRiskPercentageCount[d],
            "risk": d,
            "playersRiskPercentages": playersRisks,
        })
    })
    .then(
        response => response.json()
    )
    .then(data => {
        let jsonData = JSON.parse(data.html);
        let playerPercentageBox = document.getElementById('numberOfAnalysis');
        playerPercentageBox.innerHTML = jsonData;
        setUpSliders('.swiper-container');

    })
    .catch(function (error) {
        console.log(error);
    });
}


export function varianceChart(playerStats, playerLogos) {
    const playerNames = Object.keys(playerStats); // Get the player names as labels
    const dataValues = playerNames.map(player => playerStats[player]);

    let barChartIdentifier = isMobile ? 'barChartMobile' : 'barChart';
    let canvas = document.getElementById(barChartIdentifier);

    // Adjust canvas resolution for high-DPI screens (Retina displays)
    const devicePixelRatio = window.devicePixelRatio || 1;
    const canvasWidth = isMobile ? canvas.offsetWidth : 1000;
    const canvasHeight = isMobile ? canvas.offsetHeight + 200 : 300;

    // Set canvas width and height based on the device's pixel ratio
    canvas.width = canvasWidth * devicePixelRatio;
    canvas.height = canvasHeight * devicePixelRatio;

    // Scale the canvas context to match the device's pixel ratio
    let ctx = canvas.getContext("2d");
    ctx.scale(devicePixelRatio, devicePixelRatio);

     // Set the CSS display size of the canvas (how it appears on screen)
    canvas.style.width = canvasWidth + 'px';  // Display width
    canvas.style.height = canvasHeight + 'px';  // Display height

    ctx.imageSmoothingEnabled = false; // Disable image smoothing

    const data = {
        labels: playerNames,
        datasets: [
            {
                label: 'Variance',  // Update label to reflect that it's Variance data
                data: dataValues,   // Use variance values
                fill: true,
                backgroundColor: function (context) {
                    const chart = context.chart;
                    const { ctx, chartArea } = chart;

                    // Ensure chartArea is available
                    if (!chartArea) return 'rgba(180, 225, 230, 1)';

                    // Create a linear gradient for each bar
                    const gradient = isMobile ? ctx.createLinearGradient(chartArea.left, 0, chartArea.right, 0) : ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(209, 165, 148, 1)');  // Bottom of the bar
                    gradient.addColorStop(0.2, 'rgba(241, 98, 58, 1)');
                    gradient.addColorStop(0.4, 'rgba(241, 98, 58, 1)');
                    gradient.addColorStop(0.8, 'rgba(241, 98, 58, 1)');
                    gradient.addColorStop(1, 'rgba(241, 98, 58, 1)');    // Top of the bar
                    return gradient;
                    },
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
                left:  0, // Add left padding for mobile, adjust as needed
                right: 0, // Optional: Add right padding if needed
                top: 0, // Optional: Add top padding if needed
                bottom: 0 // Optional: Add bottom padding if needed
            }
        },
        scales: {
            y: {
                beginAtZero: !isMobile,
                padding: isMobile ? 25 : 0,
                grid: {
                    color: "rgba(255, 255, 255, 1)",
                    display: !isMobile
                    // Color of the angle lines
                },
                ticks: {
                    padding: isMobile ? 15 : 0,
                    color: "rgba(255, 255, 255, 1)", // Color of the angle lines
                    display: isMobile // Hide y-axis labels
                }
            },
            x: {
                beginAtZero: isMobile,
                ticks: {
                    padding: 0, // Add padding at the bottom for x-axis labels on mobile
                    color: "rgba(255, 255, 255, 1)" // Color of the angle lines
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

            data.labels.forEach((playerName, index) => {
                const img = new Image();
                const playerLogo = playerLogos[playerName];  // Get the player's logo
                img.src = getPlayerImageFromName(playerLogo);
                if (!isMobile) {
                    ctx.drawImage(img,x.getPixelForValue(index) - 25,x.top - 40,50,37);
                }
            })

            ctx.restore();
        },

        defaults: {
            color: 'lightGreen'
        }
    }


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
