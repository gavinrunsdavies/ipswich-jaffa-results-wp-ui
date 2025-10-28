  <style>
    .summary-container {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
      max-width: 700px;
      width: 100%;
      transition: all 0.3s ease;
    }

    .summary-container h2 {
      margin-bottom: 1rem;
      font-size: 1.3rem;
      color: #333;
    }

    .summary-content {
      line-height: 1.6;
      font-size: 1rem;
    }

    .controls {
      display: flex;
      justify-content: space-between;
      margin-top: 1.5rem;
      width: 100%;
      max-width: 700px;
    }

    button {
      background-color: #0073aa;
      color: #fff;
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
    <h3>On This Day in JAFFA History</h3>
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
    const CACHE_PREFIX = "ai_summary_";
    const CACHE_EXPIRY_HOURS = 24;

    function formatDate(offset) {
      const date = new Date();
      date.setDate(date.getDate() + offset);
      return date.toISOString().split("T")[0];
    }
	
	function formatDateToMonthAndDay(offset) {
	  const date = new Date();
	  date.setDate(date.getDate() - offset);

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
      currentDayOffset--;
      fetchSummary(currentDayOffset);
    });

    nextBtn.addEventListener("click", () => {
      currentDayOffset++;
      fetchSummary(currentDayOffset);
    });

    // Load today by default
    fetchSummary(currentDayOffset);
  </script>
