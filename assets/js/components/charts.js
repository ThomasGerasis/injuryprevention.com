import Chart from 'chart.js/auto';

export function playerMovementChart() {
    const data = {
        labels: [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ],
        datasets: [
            {
                label: 'My First Dataset',
                data: [65, 59, 90, 81, 56, 55, 40, 65, 59, 90, 81],
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
            legend: {
                display: false // Hide dataset labels
            }
        },
        elements: {
            line: {
                borderWidth: 3
            }
        },
        scales: {
            r: {
                grid: {
                    circular: true,
                    // color: "transparent" // Transparent color for the grid lines
                },
                ticks: {
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
            let chartWidthHeight = window.innerWidth < 978 ? 200 : 400;
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

// export function riskChart() {
//
// }

export function varianceChart() {
    const isMobile = window.innerWidth <= 768; // Check if the device is mobile (width <= 768px) indexAxis based on device type
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

