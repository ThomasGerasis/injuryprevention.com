import "../../scss/shortcodes/searchArticles.scss";

window.addEventListener('click', function(event){   
    //if(document.getElementById('searchContainer').classList.contains('focused')) return true;
    let target = event.target || event.currentTarget;
    if (document.getElementById('searchInnerContainer').contains(target)){
        if(document.getElementById('searchInput').value !== ''){
            document.getElementById('searchContainer').classList.add('focused');
        }else{
            document.getElementById('searchContainer').classList.add('active');
        }
        document.querySelector('.search-container-box').classList.add('active');
        return false;
    } else{
        document.getElementById('searchContainer').classList.remove('active');
        document.getElementById('searchContainer').classList.remove('focused');
        document.querySelector('.search-container-box').classList.remove('active');
    }
});

let timeout;
let term = '';
let minChars = 3;
document.getElementById('searchInput').addEventListener("input", function(event){
    term = event.target.value;
    if(event.target.value === ''){
        document.getElementById('searchContainer').classList.remove('active');
        document.getElementById('searchContainer').classList.remove('focused');
        document.querySelector('.search-results-text').innerHTML = 'Write something';
        return true;
    }
    if(term.length < minChars){
        return true;
    }
    clearTimeout(timeout);
    document.getElementById('searchContainer').classList.remove('active');
    document.getElementById('searchContainer').classList.add('focused');
    document.querySelector('.search-results').innerHTML = '';
    document.querySelector('.search-results-text').innerHTML = 'Searching...';
    document.querySelector('.search-results').classList.add('loading');
    timeout = setTimeout(function(){
        searchArticles(term).then((data) => {
            document.querySelector('.search-results').classList.remove('loading');
            document.querySelector('.search-results-text').innerHTML = 'Search results';
            document.querySelector('.search-results').innerHTML = showResults(data, term);
        })
        .catch((err) => {
            console.log("error", err.message);
        });
    }, 400);        
});


let regexp_special_chars = new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\-]', 'g');
function regexp_escape(term) {
    return term.replace(regexp_special_chars, '\\$&');
}

// Highlight the query part of the search term
function highlight_term(value, term) {
    return value.replace(
    new RegExp(
        "(?![^&;]+;)(?!<[^<>]*)(" + regexp_escape(term) + ")(?![^<>]*>)(?![^&;]+;)",
        "gi"
    ), function(match, p1) {
        return "<b>" + p1 + "</b>";
    }
    );
}

function find_value_and_highlight_term(template, value, term) {
    return template.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + regexp_escape(value) + ")(?![^<>]*>)(?![^&;]+;)", "g"), highlight_term(value, term));
}

function showResults(data, term) {
    if(data && data.length) {
       let newHtml = '';
       let newItem = '';

        // add each article matches search result in html variable
        data.forEach(function(value, index) {
            newItem = '<a href="'+value.url+'" class="search-result">'+value.title+'</a>';
            newItem = find_value_and_highlight_term(newItem, value.title, term);
            newHtml += newItem;
        });
        
        return newHtml;
    }
    return "<p>No results found!</p>";
}


async function searchArticles(term) {
    let siteUrl = window.location.origin;
    const response = await fetch(siteUrl + '/searchResults/'+term, {
        method: "GET",
        headers: {
            'Accept': 'application/json',
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
    });

    if (response.status !== 200) {
        throw new Error("cannot fetch data");
    }

    return await response.json();
}