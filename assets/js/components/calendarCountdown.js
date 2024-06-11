function CountdownTracker(label, value){

    let el = document.createElement('span');
    el.className = 'flip-clock__piece d-flex flex-column align-items-center';
    el.innerHTML = '<b class="flip-clock__card clock-card d-block position-relative"><b class="card__top"></b><b class="card__bottom"></b><b class="card__back"><b class="card__bottom"></b></b></b>' +
        '<span class="flip-clock__slot">' + label + '</span>';

    this.el = el;

    var top = el.querySelector('.card__top'),
        bottom = el.querySelector('.card__bottom'),
        back = el.querySelector('.card__back'),
        backBottom = el.querySelector('.card__back .card__bottom');

    this.update = function(val){
        val = ( '0' + val ).slice(-2);
        if ( val !== this.currentValue ) {

            if ( this.currentValue >= 0 ) {
                back.setAttribute('data-value', this.currentValue);
                bottom.setAttribute('data-value', this.currentValue);
            }
            this.currentValue = val;
            top.innerText = this.currentValue;
            backBottom.setAttribute('data-value', this.currentValue);

            this.el.classList.remove('flip');
            void this.el.offsetWidth;
            this.el.classList.add('flip');
        }
    }

    this.update(value);
}

// Calculation adapted from https://www.sitepoint.com/build-javascript-countdown-timer-no-dependencies/
function getTimeRemaining(endtime) {
    var t = Date.parse(endtime) - Date.parse(new Date());
    return {
        'Total': t,
        // 'ΗΜΕΡΕΣ': Math.floor(t / (1000 * 60 * 60 * 24)),
        'HOURS': Math.floor((t / (1000 * 60 * 60)) % 24),
        'MINUTES': Math.floor((t / 1000 / 60) % 60),
        'SECONDS': Math.floor((t / 1000) % 60)
    };
}


function getTime() {
    var t = new Date();
    return {
        'Total': t,
        'HOURS': t.getHours() % 12,
        'MINUTES': t.getMinutes(),
        'SECONDS': t.getSeconds()
    };
}

export function Clock(countdown,callback) {
    countdown = countdown ? new Date(Date.parse(countdown)) : false;
    callback = callback || function(){};
    let updateFn = countdown ? getTimeRemaining : getTime;

    this.el = document.createElement('div');
    this.el.className = 'flip-clock d-flex justify-content-center';

    let trackers = {},
        t = updateFn(countdown),
        key, timeinterval;

    for ( key in t ){
        if ( key === 'Total' ) { continue; }
        trackers[key] = new CountdownTracker(key, t[key]);
        this.el.appendChild(trackers[key].el);
    }

    let i = 0;
    function updateClock() {
        timeinterval = requestAnimationFrame(updateClock);
        // throttle so it's not constantly updating the time.
        if ( i++ % 10 ) { return; }
        let t = updateFn(countdown);
        if ( t.Total < 0 ) {
            cancelAnimationFrame(timeinterval);
            for ( key in trackers ){
                trackers[key].update( 0 );
            }
            callback();
            return;
        }
        for ( key in trackers ){
            trackers[key].update( t[key] );
        }
    }

    setTimeout(updateClock,500);
}

