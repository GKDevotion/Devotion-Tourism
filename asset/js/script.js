
        // get time parts reliably for a timezone using Intl.DateTimeFormat
        function getTimePartsForTimeZone(timeZone) {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone,
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            // formatter.formatToParts returns an array with {type,value}
            const parts = formatter.formatToParts(new Date());
            const map = {};
            parts.forEach(p => { if (p.type !== 'literal') map[p.type] = p.value; });
            return {
                hour: parseInt(map.hour, 10),
                minute: parseInt(map.minute, 10),
                second: parseInt(map.second, 10)
            };
        }

        function updateClock(elementId, timezone) {
            const { hour, minute, second } = getTimePartsForTimeZone(timezone);
            // hour in 12-hour for analog
            const hour12 = hour % 12;
            const hourDeg = (hour12 + minute / 60 + second / 3600) * 30; // 360/12 = 30
            const minDeg = (minute + second / 60) * 6; // 360/60 = 6
            const secDeg = second * 6;

            const clock = document.getElementById(elementId);
            if (!clock) return;

            // set CSS vars used by child .hand elements
            clock.style.setProperty('--hour-deg', `${hourDeg}deg`);
            clock.style.setProperty('--min-deg', `${minDeg}deg`);
            clock.style.setProperty('--sec-deg', `${secDeg}deg`);
        }

        function runClocks() {
            updateClock("clock-sg", "Asia/Singapore");
            updateClock("clock-nd", "Asia/Kolkata");      // New Delhi -> Asia/Kolkata
            updateClock("clock-dubai", "Asia/Dubai");
            updateClock("clock-london", "Europe/London");
            updateClock("clock-ny", "America/New_York");
        }

        // update every 250ms for smooth seconds
        setInterval(runClocks, 250);
        runClocks();
