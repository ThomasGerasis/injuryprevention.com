import "../scss/timeline.scss";
import {setUpSliders} from "./customSwiper";
import {varianceChart, playerMovementChart} from "./components/charts";

const siteUrl = window.location.origin;
const ajaxUrl = siteUrl + '/ajaxFunctions/';

document.addEventListener('DOMContentLoaded', function () {
    const gamesContainer = document.querySelector('.matches-container');
    const sliderContainer = gamesContainer.querySelector('.outer-container');
    const controls = gamesContainer.querySelector('.controls');
    const controlMatches = controls.querySelector('.matches');
    const controlPlayers = controls.querySelector('.players');
    // const controlRisk = controls.querySelector('.risk');

    controlMatches.addEventListener("click", () => {
        fetchMatches(gamesContainer,sliderContainer,controls,controlPlayers);
    });

    controlPlayers.addEventListener("click", () => {
        fetchPlayers(controlPlayers,gamesContainer,sliderContainer,controls,controlPlayers);
    });


    let matches = document.querySelectorAll('.timeline-slide');
    matches.forEach((game) => {
        game.addEventListener("click", () => {
            fetchPlayers(game,gamesContainer,sliderContainer,controls,controlPlayers);
        });

    });

    // controlRisk.addEventListener("click", () => {
    //     fetchRiskGraph(controlPlayers,gamesContainer,sliderContainer,controls,controlPlayers);
    // });

}, false);

window.fetchMatches = function(gamesContainer,sliderContainer,controls,controlPlayers){
    fetch(ajaxUrl + 'fetchMatches', {
        method: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json'
            // "X-Requested-With": "XMLHttpRequest"
        },
    })
        .then(
            response => response.json()
        )
        .then(data => {
            let jsonData = JSON.parse(data.html);
            gamesContainer.classList.add('slider-container');
            gamesContainer.classList.remove('slider-step-container');
            sliderContainer.innerHTML = jsonData;
            setUpSliders('.swiper-container');
            controls.style.display = 'none';

            let matches = document.querySelectorAll('.timeline-slide');
            matches.forEach((game) => {
                game.addEventListener("click", () => {
                    fetchPlayers(game,gamesContainer,sliderContainer,controls,controlPlayers);
                });

            });

        })
        .catch(function (error) {
            console.log(error);
        });
}


window.fetchPlayers = function(game,gamesContainer,sliderContainer,controls,controlPlayers){
    let gameDate = game.dataset.date;
    let gameOpponent = game.dataset.opponent;
    fetch(ajaxUrl + 'fetchPlayers', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "gameDate": gameDate,
            "gameOpponent": gameOpponent,
        })
    })
        .then(
            response => response.json()
        )
        .then(data => {
            let jsonData = JSON.parse(data.html);
            gamesContainer.classList.remove('slider-container');
            gamesContainer.classList.add('slider-step-container');
            sliderContainer.innerHTML = jsonData;
            setUpSliders('.swiper-container');

            let players = document.querySelectorAll('.player-row-slide');
            players.forEach((player) => {
                player.addEventListener("click", () => {
                    buildPlayerChart(player,gameDate,gameOpponent,sliderContainer,controlPlayers);
                });
            });

            controls.style.display = 'flex';
            controlPlayers.style.display = 'none';

        })
        .catch(function (error) {
            console.log(error);
        });
}

// window.fetchRiskGraph = function(game,gamesContainer,sliderContainer,controls,controlPlayers){
//     let gameDate = game.dataset.date;
//     let gameOpponent = game.dataset.opponent;
//     fetch(ajaxUrl + 'fetchRisk', {
//         method: "POST",
//         headers: {
//             'Accept': 'application/json',
//             'Content-type': 'application/json',
//             "X-Requested-With": "XMLHttpRequest"
//         },
//         body: JSON.stringify({
//             "gameDate": gameDate,
//             "gameOpponent": gameOpponent,
//         })
//     })
//         .then(
//             response => response.json()
//         )
//         .then(data => {
//             let jsonData = JSON.parse(data.html);
//             gamesContainer.classList.remove('slider-container');
//             gamesContainer.classList.add('slider-step-container');
//             sliderContainer.innerHTML = jsonData;
//             controls.style.display = 'flex';
//             controlPlayers.style.display = 'none';
//             riskChart()
//         })
//         .catch(function (error) {
//             console.log(error);
//         });
// }


window.buildPlayerChart = function (player,gameDate,gameOpponent,sliderContainer,controlPlayers) {
    let playerName = player.dataset.name;
    let playerLogo = player.dataset.img;
    fetch(ajaxUrl + 'buildPlayerChart', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "gameDate": gameDate,
            "gameOpponent": gameOpponent,
            "playerName": playerName,
            "playerLogo": playerLogo
        })
    })
    .then(
        response => response.json()
    )
    .then(data => {
        let jsonData = JSON.parse(data.html);
        sliderContainer.innerHTML = jsonData;
        controlPlayers.style.display = 'block';
        controlPlayers.dataset.opponent = gameOpponent;
        controlPlayers.dataset.date = gameDate;
        playerMovementChart();
        // runColoring();/
        document.querySelector('.player-image').addEventListener("click", () => {
            fetchVariance(player,gameDate,gameOpponent,sliderContainer,controlPlayers);
        });

    })
    .catch(function (error) {
        console.log(error);
    });

}

window.fetchVariance = function(player,gameDate,gameOpponent,sliderContainer,controlPlayers){
    fetch(ajaxUrl + 'fetchVariance', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "gameDate": gameOpponent,
            "gameOpponent": gameOpponent,
        })
    })
        .then(
            response => response.json()
        )
        .then(data => {
            let jsonData = JSON.parse(data.html);
            sliderContainer.innerHTML = jsonData;
            varianceChart();
        })
        .catch(function (error) {
            console.log(error);
        });
}



