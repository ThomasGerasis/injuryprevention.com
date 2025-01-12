import "../scss/timeline.scss";
import {setUpSliders} from "./customSwiper";
import {varianceChart, playerMovementChart, riskChart} from "./components/charts";

const siteUrl = window.location.origin;
const ajaxUrl = siteUrl + '/ajaxFunctions/';

const gamesContainer = document.querySelector('.matches-container');
const sliderContainer = gamesContainer.querySelector('.outer-container');

document.addEventListener('DOMContentLoaded', function () {
    handleMatches();
}, false);

function removeClassesFromParent(classes) {
    classes.forEach((className) => {
        if (gamesContainer.classList.contains(className)) {
            gamesContainer.classList.remove(className);
        }
    });
}



function handleMatches() {
    let matches = document.querySelectorAll('.timeline-slide');
    matches.forEach((game) => {
        game.addEventListener("click", () => {
            localStorage.setItem('activeMatch',game.dataset.slide);
            localStorage.setItem('activeMonth',game.dataset.month);
            localStorage.setItem('activeYear',game.dataset.year);
            if (game.classList.contains('injury')) {
                fetchInjuries(game);
            }else{
                fetchPlayers(game);
            }
        });
    });
}

window.fetchMatches = function(){
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
            removeClassesFromParent(['slider-step-container','risk','players']);

            sliderContainer.innerHTML = jsonData;
            setUpSliders('.swiper-container');
            handleMatches();
        })
        .catch(function (error) {
            console.log(error);
        });
}


window.fetchPlayers = function(game){
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
            gamesContainer.classList.add('slider-step-container');
            gamesContainer.classList.add('players');
            removeClassesFromParent(['slider-container','risk']);
            sliderContainer.innerHTML = jsonData;
            setUpSliders('.swiper-container');

            let players = document.querySelectorAll('.player-row-slide');
            players.forEach((player) => {
                player.addEventListener("click", () => {
                    buildPlayerChart(game,player,gameDate,gameOpponent);
                });
            });

            controlsHandler(game,gameDate,gameOpponent);

        })
        .catch(function (error) {
            console.log(error);
        });
}

window.fetchInjuries = function(game){
    let gameDate = game.dataset.date;
    let gameOpponent = game.dataset.opponent;
    fetch(ajaxUrl + 'fetchInjuries', {
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
            removeClassesFromParent(['slider-container','risk','players']);
            gamesContainer.classList.add('slider-step-container');
            sliderContainer.innerHTML = jsonData;
            setUpSliders('.swiper-container');
            controlsHandler(game,gameDate,gameOpponent);
        })
        .catch(function (error) {
            console.log(error);
        });
}


window.fetchRiskGraph = function(game,gameDate, gameOpponent)
{
    fetch(ajaxUrl + 'fetchRisk', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "game": game,
            "gameDate": gameDate,
            "gameOpponent": gameOpponent,
        })
    })
        .then(
            response => response.json()
        )
        .then(data => {
            let jsonData = JSON.parse(data.html);
            let teamRiskData = JSON.parse(data.teamRisk);

            gamesContainer.classList.add('slider-step-container');
            gamesContainer.classList.add('risk');

            removeClassesFromParent(['slider-container','players']);

            sliderContainer.innerHTML = jsonData;
            riskChart(teamRiskData);
            // let teamRiskPercentageCount = teamRiskData['teamRiskCount'];
            // let playersRisks = teamRiskData['playersRiskPercentages'];
            // handleRiskScalesClick(1,playersRisks,teamRiskPercentageCount);
            controlsHandler(game,gameDate,gameOpponent);
        })
        .catch(function (error) {
            console.log(error);
        });
}


window.buildPlayerChart = function (game,player,gameDate,gameOpponent) {
    let playerName = player.dataset.name;
    let playerLogo = player.dataset.img;
    let playerKey = player.dataset.key;
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
            "playerLogo": playerLogo,
            "playerKey": playerKey
        })
    })
    .then(
        response => response.json()
    )
    .then(data => {
        let jsonData = JSON.parse(data.html);
        let playerMovementData = JSON.parse(data.playerMovementData);
        sliderContainer.innerHTML = jsonData;
        playerMovementChart(playerMovementData);
        controlsHandler(game,gameDate,gameOpponent);
        removeClassesFromParent(['risk','players']);
    })
    .catch(function (error) {
        console.log(error);
    });

}

window.fetchVariance = function(game,gameDate,gameOpponent){
    fetch(ajaxUrl + 'fetchVariance', {
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
        sliderContainer.innerHTML = jsonData;
        let playersVariation = JSON.parse(data.playersVariation);
        let playerLogos = JSON.parse(data.playerLogos);
        varianceChart(playersVariation,playerLogos);
        controlsHandler(game,gameDate,gameOpponent);
        removeClassesFromParent(['risk','players']);
    })
    .catch(function (error) {
        console.log(error);
    });
}


function displayInfo(infoKey)
{
    let infoModal = document.querySelector('.info-box-container');
    let infoTitle = infoModal.querySelector('.info-title');
    let infoText = infoModal.querySelector('.info-text');

    fetch(ajaxUrl + 'fetchInfo', {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-type': 'application/json',
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "infoKey": infoKey,
        })
    })
    .then(
        response => response.json()
    )
    .then(data => {
        infoModal.classList.add('d-flex');
        infoModal.classList.remove('d-none');

        infoTitle.innerText = data.title;
        infoText.innerText = data.information;

        let closeInfoModal = document.querySelector('.close-info-box');
        closeInfoModal.addEventListener("click", (e) => {
            e.preventDefault();
            hideInfo(); 
        });

    })
    .catch(function (error) {
        console.log(error);
    });
}

function hideInfo()
{
    let infoModal = document.querySelector('.info-box-container');
    infoModal.classList.remove('d-flex');
    infoModal.classList.add('d-none');
}

function controlsHandler(game,gameDate,gameOpponent)
{
    let allMatchesButton = document.querySelectorAll('.all-matches');
    if (allMatchesButton){
        allMatchesButton.forEach(element => {
            element.addEventListener("click", (event) => {
                 event.preventDefault();
                 fetchMatches();
            });
        });
    }

    let variancePlayersButton = document.querySelector('.variancePlayersButton');
    if (variancePlayersButton){
        variancePlayersButton.addEventListener("click", (e) => {
            e.preventDefault();
            fetchVariance(game,gameDate, gameOpponent);
        });
    }

    let readInfoButton = document.querySelectorAll('.infomodal');

    if (readInfoButton){
        readInfoButton.forEach(element => {
            element.addEventListener("click", (e) => {  
                e.preventDefault(); 
                displayInfo(element.dataset.info);   
            }); 
        })
    }

    let controls = document.querySelector('.controls');
    if (controls) {
        let chartButtons = document.querySelectorAll('.chartButton');
        chartButtons.forEach(element => {
            element.addEventListener("click", (event) => {
                if(element.classList.contains('active')){
                    return;
                }
                let datasetValue = element.dataset.chart;
                if (datasetValue === 'variance') {
                    fetchVariance(game,gameDate,gameOpponent);
                } else if (datasetValue === 'players') {
                    fetchPlayers(game);
                } else if (datasetValue === 'teams') {
                    fetchRiskGraph(game,gameDate, gameOpponent);
                }
            });
        });
    }
}



