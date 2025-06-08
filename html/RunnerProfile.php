<style>
    .page-header {
        display: none;
    }

    .site-content {
        padding-top: 0;
    }

    .row * {
        box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .col-50 {
        float: left;
        width: 50%;
        padding: 10px;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    .center-panel {
        text-align: center;
    }

    .section,
    .center-panel {
        margin-bottom: 1em;
        clear: both;
    }

    table.display th {
        text-align: left;
    }

    a.to-top {
        float: right;
        font-size: small;
    }
</style>
<div class="center-panel">
    <h2>My Results: <span class="runnerName"></span></h2>
    <h5 class="runnerAgeCategory"></h5>
    <h4><a href="#age-grading-chart">Age Grading Performances</a> | <a href="#certificates-panel">Certificates</a> | <a href="#insights-distance-panel">Runner Insights</a> | <span id="member-ranking-table-label"><a href="#member-ranking-table">Club rankings</a> | </span><a href="#member-race-count-table">Race distance breakdown</a> | <a href="#member-race-and-course-summary">Race & course summary</a> | <span class="seniors-only"><a href="#member-race-predictions-current">Race predictions</a> | </span><a href="#member-seasonal-best-results-table">Best known performances</a> | <a href="#member-results-table">All Results</a></h4>
</div>
<div class="center-panel">
    <h3>Age Grading Performances</h3>
    <div id="age-grading-chart" style="height: 350px;"></div>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="section">
    <div id="certificates-panel">
        <table class="display" id="member-certificates-table">
            <caption>7 Star Standard certificates achieved by <span class="runnerName"></span>.</caption>
            <caption style="font-size:smaller">All certificates are for races from 2017 onwards. Click on the standard to view or download your certificate.</caption>
            <thead>
                <tr>
                    <th>Standard</th>
                    <th>Distance</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="center-panel">
    <div id="insights-distance-panel">
        <h3>Runner Insights: Race distance <span id="runner-insights-race-distance-text"></span></h3>
        <select id="insights-race-distance-selection" style="font-size: 10px; float: right;"></select>
        <div id="insights-race-distance-chart" style="height: 350px;clear: both;"></div>
    </div>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="section">
    <table class="display" id="member-ranking-table">
        <caption>Club rankings for <span class="runnerName"></span></caption>
        <thead>
        </thead>
        <tbody>
        </tbody>
    </table>
    <p style="font-size: smaller">The above Ipswich JAFFA Running Club rankings show where <span class="runnerName"></span> ranks among other Ipswich JAFFA members (past and present). Ranking category: <span class="runnerGender"></span>.</p>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="section">
    <table class="display" id="member-race-count-table">
        <caption>Race distance breakdown for <span class="runnerName"></span></caption>
        <thead>
        </thead>
        <tbody>
        </tbody>
    </table>
    <p style="font-size: smaller">The above totals are only valid for measured race distances (e.g. those that can count towards a personal best).</p>
    <p style="font-size: smaller">The total distance covered calculation is only approximate and the accuracy of older results is not guaranteed.</p>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="row" id="member-race-and-course-summary">
    <div class="col-50">
        <h3>Race distance summary</h3>
        <div id="race-distance-chart" style="height: 250px;"></div>
    </div>
    <div class="col-50">
        <h3>Course type summary</h3>
        <div id="course-type-chart" style="height: 250px;"></div>
    </div>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="section seniors-only">
    <div id="member-race-predictions-current">
        <table class="display" id="member-race-predictions-current-table">
            <caption>Race predictions based on best known performances for last year of competition (<span class="lastYearOfCompetition"></span>)</caption>
            <thead>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="member-race-predictions-best">
        <table class="display" id="member-race-predictions-best-table">
            <caption>Race predictions based on best known performances.</caption>
            <thead>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <p style="font-size: smaller">The above race predictions are based on the known performances and calculated using the formula: T2 = T1 x (D2/D1)^1.06, where D1 is known distance, D2 is target distance, T1 is result for distance D1 and T2 is predicted time for target distance D2. Read predictions in columns not rows. Entries in bold show the achieved time (T1).</p>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
    <br />
</div>
<div class="section">
    <table class="display" id="member-seasonal-best-results-table">
        <caption>The best known performances for <span class="runnerName"></span></caption>
        <thead>
        </thead>
        <tbody>
        </tbody>
    </table>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>
<div class="section">
    <table class="display" id="member-results-table">
        <caption>All race results for <span class="runnerName"></span></caption>
        <thead>
            <tr>
                <th>Race Id</th>
                <th data-priority="2">Race</th>
                <th data-priority="1">Date</th>
                <th data-priority="4">Position</th>
                <th data-priority="3">Result</th>
                <th data-priority="6">Personal Best</th>
                <th data-priority="7">Standard</th>
                <th data-priority="8">Info</th>
                <th data-priority="5">Age Grading</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <a class="to-top" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>

<!-- amCharts javascript sources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        var _allDistances;

        const seniorDistanceIds = [14, 1, 2, 3, 4, 5, 7, 8];
        const juniorDistanceIds = [10, 11, 12, 21, 13, 14, 1, 2];

        $.when(
            $.getJSON('<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/runners/<?php echo $_GET['runner_id']; ?>'),
            $.getJSON('<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/distances'),
            $.getJSON('<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/results/runner/<?php echo $_GET['runner_id']; ?>')
        ).then(function(runnerData, distancesData, resultsData) {
            // Each item is an array: [data, statusText, jqXHR]
            var runner = runnerData[0];
            var distances = distancesData[0];
            var results = resultsData[0];

            _allDistances = distances;

            // UI population
            $('.runnerName').text(runner.name);
            $('.runnerAgeCategory').text(runner.ageCategory);
            $('.runnerGender').text(runner.sex);
            populateCertificatesTable(runner.name, runner.certificates);
            createResultsDataTable(results);

            // Determine distances based on age
            var seniorDataOnly = runner.ageAtLastRace >= 16;
            const distanceIds = seniorDataOnly ? seniorDistanceIds : juniorDistanceIds;

            populateRankingsTable(runner.rankings);
            processResults(results, distanceIds, seniorDataOnly);
        });

        async function processResults(data, distanceIds, seniorDataOnly) {
            // Get PBs by year. Data returned sorted by date (descending)
            var seasonalBest = [];
            var personalBest = [];
            var raceDistanceCount = [];
            var courseTypeCount = [];
            var otherRaceDistanceCount = 0;
            var validCourseTypeIds = ["1", "3", "6", "8"];
            var percentageGradingData = [];

            $.each(data, function(i, result) {

                // isSeasonalBest can't be trusted.
                if (validCourseTypeIds.indexOf(result.courseTypeId) !== -1) {
                    var resultYear = result.date.substring(0, 4);
                    if (seasonalBest[resultYear] === undefined) {
                        seasonalBest[resultYear] = [];
                        seasonalBest[resultYear][result.distanceId] = result;
                    } else {
                        if (seasonalBest[resultYear][result.distanceId] === undefined || (result.result < seasonalBest[resultYear][result.distanceId].result ||
                                (result.result == seasonalBest[resultYear][result.distanceId].result && result.date < seasonalBest[resultYear][result.distanceId].date))) {
                            seasonalBest[resultYear][result.distanceId] = result;
                        }
                    }
                }

                if (result.isPersonalBest == 1) {
                    if (personalBest[result.distanceId] === undefined || personalBest[result.distanceId].result > result.result) {
                        personalBest[result.distanceId] = result;
                    }
                }

                var distanceId = result.distanceId;
                if (distanceId == null)
                    distanceId = 0;
                else
                    distanceId = parseInt(distanceId);

                if (distanceIds.indexOf(distanceId) == -1 && distanceId != 0) {
                    otherRaceDistanceCount++;
                }

                if (raceDistanceCount[distanceId] === undefined)
                    raceDistanceCount[distanceId] = 0;
                raceDistanceCount[distanceId] += 1;

                var courseTypeId = result.courseTypeId;
                if (courseTypeId == null)
                    courseTypeId = 0;
                if (courseTypeCount[courseTypeId] === undefined)
                    courseTypeCount[courseTypeId] = 0;
                courseTypeCount[courseTypeId] += 1;

                if (result.percentageGrading > 0) {
                    percentageGradingData.push(result);
                }
            });

            var runnerDistanceIds = await getTopDistances(data);
            populateRaceCountTable(raceDistanceCount, otherRaceDistanceCount, runnerDistanceIds);
            populateSeasonalBestTable(seasonalBest, runnerDistanceIds);
            createRaceDistancePieChart(raceDistanceCount, otherRaceDistanceCount, runnerDistanceIds);
            createCourseTypePieChart(courseTypeCount);
            createPercentageGradingChart(percentageGradingData.reverse());
            createInsightsRaceDistancePanel(runnerDistanceIds);

            if (seniorDataOnly) {
                populateLatestRacePredictorTable(distanceIds, seasonalBest, data[0].date.substring(0, 4));
                populateAllTimeRacePredictorTable(distanceIds, personalBest);
            } else {
                $('.seniors-only').hide();
            }
        }

        function getDistance(distanceId) {
            for (var i = 0; i < _allDistances.length; i++) {
                if (parseInt(_allDistances[i].id) === distanceId) {
                    return _allDistances[i];
                }
            }

            return null;
        }

        function populateRankingsTable(rankings) {
            var tableId = '#member-ranking-table';
            if (rankings === null || rankings.length == 0) {
                $(tableId).hide();
                return;
            }

            var tableBody = $(tableId + ' tbody');
            var tableHead = $(tableId + ' thead');

            var headers = '<tr>';
            var rows = '<tr>';
            $.each(rankings, function(j, ranking) {
                headers += '<th>' + getDistance(parseInt(ranking.distanceId, 10)).text + '</th>';
                rows += '<td><strong>' + ranking.rank + '</strong><br/><small>' + ranking.event + ', ' + ranking.date + ', ' + ipswichjaffarc.formatTime(ranking.result) + '</small></td>';
            });
            headers += '</tr>';
            rows += '</tr>';

            tableHead.append(headers);
            tableBody.append(rows);

            $(tableId).DataTable({
                paging: false,
                searching: false,
                processing: false,
                ordering: false,
                info: false
            });
        }

        function populateLatestRacePredictorTable(distanceIds, data, year) {
            var tableId = '#member-race-predictions-current-table';
            var tableBody = $(tableId + ' tbody');
            var tableHead = $(tableId + ' thead');

            var rows = '';
            var headers = '<tr><th></th>';

            $.each(distanceIds, function(k, distanceId) {
                var distance = getDistance(distanceId);
                if (distance != null) {
                    headers += '<th>' + distance.text + '</th>';
                    rows += '<tr>';
                    rows += '<td>' + distance.text + '</td>';
                    $.each(distanceIds, function(k2, distanceId2) {
                        if (data[year] !== undefined) {
                            if (data[year][distanceId] !== undefined) {
                                if (distanceId == distanceId2) {
                                    rows += '<td class="success"><strong>' + ipswichjaffarc.formatTime(data[year][distanceId].result) + '</strong></td>';
                                } else {
                                    rows += '<td>' + ipswichjaffarc.formatTime(getPredictedTime(distanceId, data[year][distanceId].result, distanceId2)) + '</td>';
                                }
                            } else {
                                rows += '<td></td>';
                            }
                        } else {
                            rows += '<td></td>';
                        }
                    });
                    rows += '</tr>';
                }
            });
            headers += '</tr>';

            tableHead.append(headers);
            tableBody.append(rows);
            $('.lastYearOfCompetition').text(year);

            $(tableId).DataTable({
                paging: false,
                searching: false,
                processing: false,
                ordering: false,
                info: false
            });
        }

        function populateAllTimeRacePredictorTable(distanceIds, data) {
            var tableId = '#member-race-predictions-best-table';
            var tableBody = $(tableId + ' tbody');
            var tableHead = $(tableId + ' thead');

            var rows = '';
            var headers = '<tr><th></th>';

            $.each(distanceIds, function(k, distanceId) {
                var distance = getDistance(distanceId);
                if (distance != null) {
                    headers += '<th>' + distance.text + '</th>';
                    rows += '<tr>';
                    rows += '<td>' + distance.text + '</td>';
                    $.each(distanceIds, function(k2, distanceId2) {
                        if (data[distanceId] !== undefined) {
                            if (distanceId == distanceId2) {
                                rows += '<td class="success"><strong>' + ipswichjaffarc.formatTime(data[distanceId].result) + '</strong></td>';
                            } else {
                                rows += '<td>' + ipswichjaffarc.formatTime(getPredictedTime(distanceId, data[distanceId].result, distanceId2)) + '</td>';
                            }
                        } else {
                            rows += '<td></td>';
                        }
                    });
                    rows += '</tr>';
                }
            });
            headers += '</tr>';

            tableHead.append(headers);
            tableBody.append(rows);

            $(tableId).DataTable({
                paging: false,
                searching: false,
                processing: false,
                ordering: false,
                info: false
            });
        }

        async function getTopDistances(data) {
            const counts = {};

            // Count valid distanceIds only
            $.each(data, function(_, item) {
                if (item.distanceId !== null &&
                    item.distanceId !== undefined &&
                    item.distanceId != "0" &&
                    item.performance != "0.000") {
                    const id = item.distanceId.toString();
                    counts[id] = (counts[id] || 0) + 1;
                }
            });

            // Top 8 most frequent IDs
            const top8Ids = Object.entries(counts)
                .sort((a, b) => b[1] - a[1])
                .slice(0, 8)
                .map(entry => parseInt(entry[0], 10));

            // Get distances and sort by miles
            const distanceData = await Promise.all(
                top8Ids.map(async id => {
                    const result = await getDistance(id);
                    return {
                        id,
                        text: result.text,
                        units: result.units
                    };
                })
            );

            // Sort and return the final result
            return distanceData
                .sort((a, b) => a.units - b.units)
                .map(({
                    id,
                    text
                }) => ({
                    id,
                    text
                }));
        }

        function populateSeasonalBestTable(data, runnerDistanceIds) {
            var tableId = '#member-seasonal-best-results-table'
            var tableBody = $(tableId + ' tbody');
            var tableHead = $(tableId + ' thead');

            var headers = '<tr><th>Year</th>';
            for (var i = 0; i < runnerDistanceIds.length; i++) {
                headers += '<th>' + runnerDistanceIds[i].text + '</th>';
            }
            headers += '</tr>';

            tableHead.append(headers);

            var rows = '';

            for (var i = data.length - 1; i > 0; i--) {
                var year = i;
                if (data[year] === undefined)
                    continue;

                rows += '<tr>';
                rows += '<td>' + year + '</td>';

                $.each(runnerDistanceIds, function(k, distance) {
                    if (data[year][distance.id] !== undefined) {
                        rows += '<td>' + ipswichjaffarc.formatTime(data[year][distance.id].time) + '</td>';
                    } else {
                        rows += '<td></td>';
                    }
                });

                rows += '</tr>';
            }

            tableBody.append(rows);

            $(tableId).DataTable({
                paging: false,
                searching: false,
                processing: false,
                ordering: false,
                info: false
            });
        }

        function populateCertificatesTable(name, data) {
            if (data === null)
                return;
            var tableBody = $('#member-certificates-table tbody');

            var rows = '';

            $.each(data, function(i, cert) {
                rows += '<tr>';
                rows += '<td><a href="' + getStandardCertificatesUrl(name, cert) + '" target="_blank">' + cert.name + '</a></td>';
                rows += '<td>' + cert.distance + '</td>';
                rows += '<td>' + cert.event + ', on ' + cert.date + '. Time ' + ipswichjaffarc.formatTime(cert.result) + '</td>';
                rows += '</tr>';
            });

            tableBody.append(rows);
        }

        function populateRaceCountTable(data, otherRaceDistanceCount, distanceIds) {
            var tableId = '#member-race-count-table';
            var tableBody = $(tableId + ' tbody');
            var tableHead = $(tableId + ' thead');

            var headers = '<tr><th></th>';
            var rows = '<tr>';
            rows += '<td>Count</td>';

            for (var i = 0; i < distanceIds.length; i++) {
                headers += '<th>' + distanceIds[i].text + '</th>';
                var count = data[distanceIds[i].id] === undefined ? 0 : data[distanceIds[i].id];
                rows += '<td>' + count + '</td>';
            }
            headers += '<th>Other</th><th>Not Measured</th><th>Total</th>';
            headers += '</tr>';

            tableHead.append(headers);

            var sum = data.reduce(function(a, b) {
                return a + b;
            }, 0);

            rows += '<td>' + otherRaceDistanceCount + '</td>';
            // Not measured
            rows += '<td>' + (data[0] === undefined ? 0 : data[0]) + '</td>';
            rows += '<td>' + sum + '</td>';
            rows += '</tr>';

            rows += '<tr>';
            rows += '<td>Distance (miles)</td>';
            for (var i = 0; i < distanceIds.length; i++) {
                var miles;
                if (data[distanceIds[i].id] === undefined)
                    miles = 0;
                else
                    miles = getTotalRaceDistanceInMiles(distanceIds[i].id, data[distanceIds[i].id]).toFixed(2);
                rows += '<td>' + miles + '</td>';
            }

            var otherDistanceMilesSum = 0;
            for (var distanceId in data) {
                distanceId = parseInt(distanceId);                
				if (!distanceIds.some(d => d.id === distanceId) && distanceId != 0) {
                    otherDistanceMilesSum += getTotalRaceDistanceInMiles(distanceId, data[distanceId]);
                }
            }

            rows += '<td>' + otherDistanceMilesSum.toFixed(2) + '</td>';
            rows += '<td></td>';
            var totalDistance = data.reduce(function(total, currentValue, currentIndex) {
                return getTotalRaceDistanceInMiles(currentIndex, currentValue) + total;
            }, 0);
            rows += '<td>' + totalDistance.toFixed(2) + '</td>';
            rows += '</tr>';
            tableBody.append(rows);

            $(tableId).DataTable({
                paging: false,
                searching: false,
                processing: false,
                ordering: false,
                info: false
            });
        }

        function getTotalRaceDistanceInMiles(distanceId, number) {
            var distance = getDistance(distanceId);
            if (distance != null) {
                return (distance.miles * number);
            }

            return 0;
        }

        function getStandardCertificatesUrl(name, cert) {

            return '<?php echo plugins_url('php/standards/printcertificate.php', dirname(__FILE__)); ?>' +
                '?name=' + name +
                '&standard=' + cert.name +
                '&event=' + cert.event +
                '&date=' + cert.date +
                '&time=' + ipswichjaffarc.formatTime(cert.result) +
                '&filepath=<? echo plugin_dir_path(dirname(__FILE__)); ?>php/standards/';
        }

        function getPredictedTime(actualDistanceId, actualTime, targetDistanceId) {

            var actualDistance = getDistance(actualDistanceId);
            var targetDistance = getDistance(targetDistanceId);
            var actualTotalMinutes = timeToMinutes(actualTime);

            var targetTotalMinutes = actualTotalMinutes * (Math.pow((targetDistance.miles / actualDistance.miles), 1.06));

            var hours = Math.floor(targetTotalMinutes / 60).toString().padStart(2, '0');

            var minutes = Math.floor(targetTotalMinutes % 60).toString().padStart(2, '0');

            var seconds = Math.floor(((targetTotalMinutes % 60) - minutes) * 60).toString().padStart(2, '0');

            var targetTime = hours + ':' + minutes + ':' + seconds;

            return targetTime;
        }

        function createResultsDataTable(data) {
            $('#member-results-table').DataTable({
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                    '<td>' + col.title + ':' + '</td> ' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ?
                                $('<table/>').append(data) :
                                false;
                        }
                    }
                },
                pageLength: 20,
                serverSide: false,
                processing: true,
                autoWidth: false,
                order: [
                    [2, "desc"]
                ],
                scrollX: true,
                data: data,
                columns: [{
                        data: "raceId",
                        visible: false
                    },
                    {
                        data: "raceName",
                        render: function(data, type, row, meta) {
                            var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
                            var anchor = '<a href="' + resultsUrl;
                            anchor += '?raceId=' + row.raceId;
                            anchor += '">' + row.eventName + nullToEmptyString(data) + '</a>';
                            return anchor;
                        }
                    },
                    {
                        data: "date"
                    },
                    {
                        data: "position",
                        render: function(data, type, row, meta) {
                            return data > 0 ? data : '';
                        }
                    },
                    {
                        data: "result",
                        render: function(data, type, row, meta) {
                            return data != '00:00:00' ? ipswichjaffarc.formatTime(data) : '';
                        },
                        className: 'text-right'
                    },
                    {
                        data: "isPersonalBest",
                        render: function(data, type, row, meta) {
                            if (data == 1) {
                                return '<i class="fa fa-check" aria-hidden="true"></i>';
                            }
                            return '';
                        },
                        className: 'text-center'
                    },
                    {
                        data: "standard"
                    },
                    {
                        data: "info"
                    },
                    {
                        data: "percentageGrading",
                        render: function(data, type, row, meta) {
                            var html = data > 0 ? data + '%' : '';
                            if (row.percentageGradingBest == 1) {
                                html += ' <i style="color: #e88112;" class="fa fa-star" aria-hidden="true" title="New percenatge grading personal best"></i>'
                            }
                            return html;
                        }
                    }
                ]
            });
        }

        function nullToEmptyString(value) {
            return (value == null || value == "") ? "" : " - " + value;
        }

        function createRaceDistancePieChart(raceDistanceCount, otherRaceDistanceCount, distanceIds) {
            am4core.ready(function() {
                am4core.useTheme(am4themes_animated);

                var chart = am4core.create("race-distance-chart", am4charts.PieChart);

				for (var i = 0; i < distanceIds.length; i++) {
					var count = raceDistanceCount[distanceIds[i].id] === undefined ? 0 : raceDistanceCount[distanceIds[i].id];
					chart.data.push({
                        "distance": distanceIds[i].text,
                        "count": count
                    });
				}

                chart.data.push({
                    "distance": "Other",
                    "count": otherRaceDistanceCount
                });

                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "count";
                pieSeries.dataFields.category = "distance";
                pieSeries.innerRadius = am4core.percent(40);

                var rgm = new am4core.RadialGradientModifier();
                rgm.brightnesses.push(-0.8, -0.8, -0.5, 0, -0.5);
                pieSeries.slices.template.fillModifier = rgm;
                pieSeries.slices.template.strokeModifier = rgm;
                pieSeries.slices.template.strokeOpacity = 0.4;
                pieSeries.slices.template.strokeWidth = 0;

				pieSeries.labels.template.fontSize = 12;
            });
        }

        function createCourseTypePieChart(courses) {
            am4core.ready(function() {
                am4core.useTheme(am4themes_animated);

                var chart = am4core.create("course-type-chart", am4charts.PieChart);

                chart.data = [{
                    "course": "Road",
                    "count": courses[1]
                }, {
                    "course": "Multi-Terrain",
                    "count": courses[2]
                }, {
                    "course": "Track",
                    "count": courses[3]
                }, {
                    "course": "Fell",
                    "count": courses[4]
                }, {
                    "course": "Cross Country",
                    "count": courses[5]
                }, {
                    "course": "Indoor",
                    "count": courses[6]
                }, {
                    "course": "Park",
                    "count": courses[7]
                }, {
                    "course": "Field",
                    "count": courses[8]
                }, {
                    "course": "Unclassified",
                    "count": courses[0]
                }];

				chart.data = chart.data.filter(item => item.count > 0);

                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "count";
                pieSeries.dataFields.category = "course";
                pieSeries.innerRadius = am4core.percent(40);

                var rgm = new am4core.RadialGradientModifier();
                rgm.brightnesses.push(-0.8, -0.8, -0.5, 0, -0.5);
                pieSeries.slices.template.fillModifier = rgm;
                pieSeries.slices.template.strokeModifier = rgm;
                pieSeries.slices.template.strokeOpacity = 0.4;
                pieSeries.slices.template.strokeWidth = 0;

				pieSeries.labels.template.fontSize = 12;
            });
        }

        function createPercentageGradingChart(results) {
            am4core.ready(function() {
                am4core.useTheme(am4themes_animated);

                // Create chart instance
                var chart = am4core.create("age-grading-chart", am4charts.XYChart);
                chart.data = results;
                chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

                // Create axes
                var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.renderer.grid.template.location = 0;
                dateAxis.renderer.minGridDistance = 50;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "Percentage Grading";

                // Create series
                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "percentageGrading";
                series.dataFields.dateX = "date";
                series.strokeWidth = 3;
                series.stroke = am4core.color("#000");
                series.fillOpacity = 1;

                series.tooltipText = "{valueY}% {eventName}: {result}";
                series.tooltip.pointerOrientation = "vertical";
                series.tooltip.background.fillOpacity = 0.5;

                // Add colour gradient
                var gradient = new am4core.LinearGradient();
                gradient.addColor(am4core.color("#000"));
                gradient.addColor(am4core.color("#E88112"));
                gradient.rotation = 270;
                series.fill = gradient;

                // Add cursor
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.behavior = "panXY";
                chart.cursor.lineX.disabled = true;
                chart.cursor.xAxis = dateAxis;
                chart.cursor.snapToSeries = series;

                // Add horizontal scrollbar
                chart.scrollbarX = new am4core.Scrollbar();
                chart.scrollbarX.parent = chart.bottomAxesContainer;
            });
        }

        function timeToMinutes(time) {
            var timeComponents = time.split(':');
            return (parseInt(timeComponents[0]) * 60) + parseInt(timeComponents[1]) + (parseInt(timeComponents[2]) / 60);
        }

        function createInsightsRaceDistancePanel(distancesIds) {

            if (distancesIds.length < 1) {
                return;
            }

            var selectList = $('#insights-race-distance-selection');
			selectList.append($('<option>', {
                    value: 0,
                    text: "Please select..."
            }));
            $.each(distancesIds, function(i, item) {
                selectList.append($('<option>', {
                    value: item.id,
                    text: item.text
                }));
            });

            var chart = createInsightsRaceDistanceChart();

            selectList.change(function(e) {
                var distanceId = selectList.val();
                if (distanceId == 0)
                    return;

                setInsightsRaceDistanceChartData(chart, distanceId);
                $('#runner-insights-race-distance-text').text(selectList.find("option:selected").text());
            });

            // Set initial value to first in the list
            setInsightsRaceDistanceChartData(chart, distancesIds[0].id);
            selectList.val(distancesIds[0].id);
            $('#runner-insights-race-distance-text').text(selectList.find("option:selected").text());
        }

        function setInsightsRaceDistanceChartData(chart, distanceId) {

            $.getJSON('<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/results/runner/<?php echo $_GET['runner_id']; ?>/insights/distance/' + distanceId,
                function(data) {
                    am4core.array.each(data.raceTimes, function(a) {
                        var timeBand = Number(a["timeBand"]);
                        var fastest = Math.floor(timeToMinutes(data.fastest));
                        if (timeBand == fastest) {
                            a.colour = am4core.color("#E88112");
                            a.hideBullet = false;
                            a.bulletText = "Fastest\n[bold]" + data.fastest + "[/]";
                            a.bulletColour = am4core.color("#E88112");
                            return;
                        }

                        var mean = Math.floor(timeToMinutes(data.mean));
                        if (timeBand == mean) {
                            a.colour = am4core.color("#E88112");
                            a.hideBullet = false;
                            a.bulletText = "Average\n[bold]" + data.mean + "[/]";
                            a.bulletColour = am4core.color("#E88112");
                            return;
                        }

                        var slowest = Math.floor(timeToMinutes(data.slowest));
                        if (timeBand == slowest) {
                            a.colour = am4core.color("#E88112");
                            a.hideBullet = false;
                            a.bulletText = "Slowest\n[bold]" + data.slowest + "[/]";
                            a.bulletColour = am4core.color("#E88112");
                            return;
                        }
                    });

                    chart.data = data.raceTimes;
                });
        }

        function createInsightsRaceDistanceChart() {

            am4core.useTheme(am4themes_animated);

            // Create chart instance		
            var chart = am4core.create("insights-race-distance-chart", am4charts.XYChart);
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "timeBand";
            categoryAxis.title.text = "Race time (minutes)";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Count";

            var gradient = new am4core.LinearGradient();
            gradient.addColor(am4core.color("#000"));
            gradient.addColor(am4core.color("#eee"));
            gradient.rotation = 90;

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "count";
            series.dataFields.categoryX = "timeBand";
            series.name = "Count of results";
            series.tooltip.getFillFromObject = false;
            series.tooltip.label.fill = am4core.color("#000");
            series.tooltip.background.fill = am4core.color("#fff");
            series.columns.template.tooltipText = "Minute {categoryX}: Results [bold]{valueY}[/]";

            series.columns.template.fill = gradient;
            series.columns.template.fillOpacity = 0.8;
            series.columns.template.strokeWidth = 1;
            series.columns.template.strokeOpacity = 1;
            series.columns.template.stroke = am4core.color("#000");

            // Use "colour" on dataItem if found. 
            series.columns.template.adapter.add("fill", function(fill, target) {
                return target.dataItem && target.dataItem.dataContext.colour ? target.dataItem.dataContext.colour : fill;
            });

            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.stroke = am4core.color("#fff");
            bullet.circle.strokeWidth = 2;
            //bullet.circle.radius = 5;
            bullet.propertyFields.fill = "bulletColour"
            bullet.circle.fillOpacity = 1;
            bullet.propertyFields.tooltipText = "bulletText";

            // Disabling all bullets, except ones that are explicitly enabled via data
            bullet.disabled = true;
            bullet.propertyFields.disabled = "hideBullet";

            return chart;
        }
    });
</script>
