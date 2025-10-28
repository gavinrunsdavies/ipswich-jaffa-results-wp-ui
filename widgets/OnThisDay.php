  <style>
    .controls {
      display: flex;
      justify-content: space-between;
      margin-top: 1.5rem;
      width: 100%;
      max-width: 700px;
    }

    button {    
      border: none;
      border-radius: 0.5rem;
      padding: 0.6rem 1.2rem;
      cursor: pointer;
      transition: background-color 0.2s ease;
      font-size: 1rem;
    }

    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    .date-label {
      margin-top: 0.8rem;
      color: #666;
      font-size: 0.95rem;
    }
  </style>
  <div class="summary-container">
    <div id="summary" class="summary-content">Loading summary...</div>
    <div class="date-label" id="date-label"></div>
  </div>

  <div class="controls">
    <button id="prev-btn">← Previous</button>
    <button id="next-btn">Next →</button>
  </div>

  <script>
    const summaryDiv = document.getElementById("summary");
    const dateLabel = document.getElementById("date-label");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");

    let currentDayOffset = 0; // 0 = today, -1 = yesterday, etc.
    const MAX_PAST_DAYS = 14;
    const CACHE_PREFIX = "ai_summary_";
    const CACHE_EXPIRY_HOURS = 24;

    function formatDate(offset) {
      const date = new Date();
      date.setDate(date.getDate() + offset);
      return date.toISOString().split("T")[0];
    }
	
	function formatDateToMonthAndDay(offset) {
	  const date = new Date();
	  date.setDate(date.getDate() + offset);

	  // Only include day and month (e.g., "October 23")
	  return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'long' });
	}

    function cacheSet(key, data) {
      const entry = {
        timestamp: Date.now(),
        data: data,
      };
      localStorage.setItem(CACHE_PREFIX + key, JSON.stringify(entry));
    }

    function cacheGet(key) {
      const item = localStorage.getItem(CACHE_PREFIX + key);
      if (!item) return null;

      try {
        const entry = JSON.parse(item);
        const ageHours = (Date.now() - entry.timestamp) / (1000 * 60 * 60);
        if (ageHours > CACHE_EXPIRY_HOURS) {
          localStorage.removeItem(CACHE_PREFIX + key);
          return null;
        }
        return entry.data;
      } catch (e) {
        return null;
      }
    }

    async function fetchSummary(offset) {
      const dateStr = formatDate(offset);
      dateLabel.textContent = `Showing results for ${formatDateToMonthAndDay(offset)}`;
      summaryDiv.innerHTML = "Loading summary...";

      const cached = cacheGet(dateStr);
      if (cached) {
        summaryDiv.innerHTML = cached.content || "<em>No summary available.</em>";
        console.log(`Loaded ${dateStr} from cache`);
        return;
      }

      try {
        const res = await fetch(`/wp-json/ipswich-jaffa-api/v2/races/history?date=${dateStr}`);
        if (!res.ok) throw new Error("Network error");
        const data = await res.json();

        summaryDiv.innerHTML = data.content || "<em>No summary available for this date.</em>";
        cacheSet(dateStr, data);
      } catch (err) {
        summaryDiv.innerHTML = "<em>Failed to load summary.</em>";
      }

      prevBtn.disabled = offset <= -14; // limit to past 14 days
      nextBtn.disabled = offset >= 0;   // no future days
    }

	prevBtn.addEventListener("click", () => {
        if (currentDayOffset > -MAX_PAST_DAYS) {
          currentDayOffset -= 1; // move to an older day
          fetchSummary(currentDayOffset);
        }
	});
	
	// NEXT should go forward one day (towards today): increment offset
	nextBtn.addEventListener("click", () => {
        if (currentDayOffset < 0) {
          currentDayOffset += 1; // move toward today
          fetchSummary(currentDayOffset);
        }
	});

    // Load today by default
    fetchSummary(currentDayOffset);
  </script>
