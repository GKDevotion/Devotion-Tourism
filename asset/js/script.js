
// Clock Working Logic//

// get time parts reliably for a timezone using Intl.DateTimeFormat
function getTimePartsForTimeZone(timeZone) {
  const formatter = new Intl.DateTimeFormat("en-US", {
    timeZone,
    hour12: false,
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  });
  // formatter.formatToParts returns an array with {type,value}
  const parts = formatter.formatToParts(new Date());
  const map = {};
  parts.forEach((p) => {
    if (p.type !== "literal") map[p.type] = p.value;
  });
  return {
    hour: parseInt(map.hour, 10),
    minute: parseInt(map.minute, 10),
    second: parseInt(map.second, 10),
  };
}

const clockState = {};

function updateClock(elementId, timezone) {
  const { hour, minute, second } = getTimePartsForTimeZone(timezone);

  const clock = document.getElementById(elementId);
  if (!clock) return;

  // init state
  if (!clockState[elementId]) {
    clockState[elementId] = { lastSecond: second, baseRotation: 0 };
  }

  const state = clockState[elementId];

  // detect second reset (59 → 0)
  if (second < state.lastSecond) {
    state.baseRotation += 360;
  }

  state.lastSecond = second;

  // angles
  const hourDeg = ((hour % 12) + minute / 60 + second / 3600) * 30;

  const minDeg = (minute + second / 60) * 6;

  const secDeg = state.baseRotation + second * 6;

  clock.style.setProperty("--hour-deg", `${hourDeg}deg`);
  clock.style.setProperty("--min-deg", `${minDeg}deg`);
  clock.style.setProperty("--sec-deg", `${secDeg}deg`);
}

function runClocks() {
  updateClock("clock-sg", "Asia/Singapore");
  updateClock("clock-nd", "Asia/Kolkata"); // New Delhi -> Asia/Kolkata
  updateClock("clock-dubai", "Asia/Dubai");
  updateClock("clock-london", "Europe/London");
  updateClock("clock-ny", "America/New_York");
}

// update every 250ms for smooth seconds
setInterval(runClocks, 250);
runClocks();
