let currency = "USD";
const defaultChartConfig = {
    type: 'bar',
    data: {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
        ],
        datasets: [
            {
                label: 'Dataset 1',
                data: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120]
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        interaction: {
            mode: "index",
            intersect: false,
        },
    },
};

const copyObject = () => {
    const temp = structuredClone(defaultChartConfig)
    temp.options.plugins = {
        tooltip: {
            callbacks: {
                label: function (context) {
                    return `${context.dataset.label}: ${new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: currency
                    }).format(context.parsed.y)}`;
                }
            }
        }
    }

    return temp;
};

// loader elements
// sales loader
const submittedProdLoader = document.getElementById('submitted-prod-loader');
const submittedProdComparisonLoader = document.getElementById('submitted-prod-comparison-loader');
const ytdSubmittedProdLoader = document.getElementById('ytd-submitted-prod-loader');
const paidAnnuityLoader = document.getElementById('paid-annuity-loader');
const paidAnnuityDtiLoader = document.getElementById('paid-annuity-dti-loader');
const pendingBusinessLoader = document.getElementById('pending-business-loader');
const openOpportunitiesLoader = document.getElementById('open-opportunities-loader');
//marketing loader
const leadsGeneratedLoader = document.getElementById('leads-generated-loader');
const ApptScheduledLoader = document.getElementById('appt-scheduled-loader');
const stickRatioShowRateLoader = document.getElementById('stick-ratio-show-rate-loader');
const seminarResponseRatesLoader = document.getElementById('seminar-response-rates-loader');
const costPerClientLoader = document.getElementById('cost-per-client-loader');
const openLeadsPerFunnelLoader = document.getElementById('open-leads-per-funnel-loader');
const leadsPerFunnelAverageLoader = document.getElementById('leads-per-funnel-average-loader');
// financial loaders
const cashOnHandLoader = document.getElementById('cash-on-hand-loader');
// const cashFlowForecastLoader = document.getElementById('cash-flow-forecast-loader');
const currentLiabilitiesLoader = document.getElementById('current-liabilities-loader');
const profitabilityPercentageLoader = document.getElementById('profitability-percentage-loader');
const monthlyExpensesLoader = document.getElementById('monthly-expenses-loader');
// operations loaders
const newAssetsFromExistingClientsLoader = document.getElementById('new-assets-from-existing-clients-loader');
const daysToIssueLoader = document.getElementById('days-to-issue-loader');
const reviewPreparedLoader = document.getElementById('review-prepared-loader');


// chart elements
const submittedProdChartEle = document.getElementById('submitted-prod-chart');
const submittedProdComparisonChartEle = document.getElementById('submitted-prod-comparison-chart');
const ytdSubmittedProdChartEle = document.getElementById('ytd-submitted-prod-chart');
const paidAnnuityChartEle = document.getElementById('paid-annuity-chart');
const paidAnnuityDtiChartEle = document.getElementById('paid-annuity-dti-chart');
const pendingBusinessChartEle = document.getElementById('pending-business-chart');
const openOpportunitiesChartEle = document.getElementById('open-opportunities-chart');
const leadsGeneratedChartEle = document.getElementById('leads-generated-chart');
const apptScheduledChartEle = document.getElementById('appt-scheduled-chart');
const stickRatioShowRateChartEle = document.getElementById('stick-ratio-show-rate-chart');
const seminarResponseRatesChartEle = document.getElementById('seminar-response-rates-chart');
const costPerClientChartEle = document.getElementById('cost-per-client-chart');
const openLeadsPerFunnelChartEle = document.getElementById('open-leads-per-funnel-chart');
const leadsPerFunnelAverageChartEle = document.getElementById('leads-per-funnel-average-chart');
const cashOnHandChartEle = document.getElementById('cash-on-hand-chart');
// const cashFlowForecastChartEle = document.getElementById('cash-flow-forecast-chart');
const currentLiabilitiesChartEle = document.getElementById('current-liabilities-chart');
const profitabilityPercentageChartEle = document.getElementById('profitability-percentage-chart');
const monthlyExpensesChartEle = document.getElementById('monthly-expenses-chart');
const newAssetsFromExistingClientsChartEle = document.getElementById('new-assets-from-existing-clients-chart');
const daysToIssueChartEle = document.getElementById('days-to-issue-chart');
const reviewPreparedChartEle = document.getElementById('review-prepared-chart');

// sales
const submittedProdChart = new Chart(
    submittedProdChartEle,
    copyObject()
);
const submittedProdComparisonChart = new Chart(
    submittedProdComparisonChartEle,
    copyObject()
);
const ytdSubmittedProdChart = new Chart(
    ytdSubmittedProdChartEle,
    copyObject()
);
const paidAnnuityChart = new Chart(
    paidAnnuityChartEle,
    copyObject()
);
const paidAnnuityDtiChart = new Chart(
    paidAnnuityDtiChartEle,
    copyObject()
);
const pendingBusinessChart = new Chart(
    pendingBusinessChartEle,
    copyObject()
);
const openOpportunitiesChart = new Chart(
    openOpportunitiesChartEle,
    copyObject()
);

//marketing
const leadsGeneratedChart = new Chart(
    leadsGeneratedChartEle,
    copyObject()
);
const apptScheduledChart = new Chart(
    apptScheduledChartEle,
    copyObject()
);
const stickRatioShowRateChart = new Chart(
    stickRatioShowRateChartEle,
    copyObject()
);
const seminarResponseRatesChart = new Chart(
    seminarResponseRatesChartEle,
    copyObject()
);
const costPerClientChart = new Chart(
    costPerClientChartEle,
    copyObject()
);
const openLeadsPerFunnelChart = new Chart(
    openLeadsPerFunnelChartEle,
    copyObject()
);
const leadsPerFunnelAverageChart = new Chart(
    leadsPerFunnelAverageChartEle,
    copyObject()
);

// financial
const cashOnHandChart = new Chart(
    cashOnHandChartEle,
    copyObject()
);
// const cashFlowForecastChart = new Chart(
//     cashFlowForecastChartEle,
//     structuredClone(defaultChartConfig)
// );
const currentLiabilitiesChart = new Chart(
    currentLiabilitiesChartEle,
    copyObject()
);

const profitabilityChartConfig = copyObject();
profitabilityChartConfig.options.plugins = {
    tooltip: {
        callbacks: {
            label: function (context) {
                return `${context.dataset.label}: ${context.parsed.y} %`;
            }
        }
    }
};
const profitabilityPercentageChart = new Chart(
    profitabilityPercentageChartEle,
    profitabilityChartConfig
);
const monthlyExpensesChart = new Chart(
    monthlyExpensesChartEle,
    copyObject()
);


// operations
const newAssetsFromExistingClientsChart = new Chart(
    newAssetsFromExistingClientsChartEle,
    copyObject()
);
const daysToIssueChart = new Chart(
    daysToIssueChartEle,
    copyObject()
);
const reviewPreparedChart = new Chart(
    reviewPreparedChartEle,
    copyObject()
);



setTimeout(() => {
    submittedProdLoader.style.display = 'none';
    submittedProdComparisonLoader.style.display = 'none';
    ytdSubmittedProdLoader.style.display = 'none';
    paidAnnuityLoader.style.display = 'none';
    paidAnnuityDtiLoader.style.display = 'none';
    pendingBusinessLoader.style.display = 'none';
    openOpportunitiesLoader.style.display = 'none';
    //marketing loader
    ApptScheduledLoader.style.display = 'none';
    stickRatioShowRateLoader.style.display = 'none';
    seminarResponseRatesLoader.style.display = 'none';
    costPerClientLoader.style.display = 'none';
    openLeadsPerFunnelLoader.style.display = 'none';
    leadsPerFunnelAverageLoader.style.display = 'none';
    // financial loaders
    cashOnHandLoader.style.display = 'none';
    // cashFlowForecastLoader.style.display = 'none';
    currentLiabilitiesLoader.style.display = 'none';
    profitabilityPercentageLoader.style.display = 'none';
    monthlyExpensesLoader.style.display = 'none';
    // operations loaders
    newAssetsFromExistingClientsLoader.style.display = 'none';
    daysToIssueLoader.style.display = 'none';
    reviewPreparedLoader.style.display = 'none';



    submittedProdChartEle.parentNode.style.display = 'block';
    submittedProdComparisonChartEle.parentNode.style.display = 'block';
    ytdSubmittedProdChartEle.parentNode.style.display = 'block';
    paidAnnuityChartEle.parentNode.style.display = 'block';
    paidAnnuityDtiChartEle.parentNode.style.display = 'block';
    pendingBusinessChartEle.parentNode.style.display = 'block';
    openOpportunitiesChartEle.parentNode.style.display = 'block';
    //marketing loader
    apptScheduledChartEle.parentNode.style.display = 'block';
    stickRatioShowRateChartEle.parentNode.style.display = 'block';
    seminarResponseRatesChartEle.parentNode.style.display = 'block';
    costPerClientChartEle.parentNode.style.display = 'block';
    openLeadsPerFunnelChartEle.parentNode.style.display = 'block';
    leadsPerFunnelAverageChartEle.parentNode.style.display = 'block';
    // financial loaders
    cashOnHandChartEle.parentNode.style.display = 'block';
    // cashFlowForecastChartEle.parentNode.style.display = 'block';
    currentLiabilitiesChartEle.parentNode.style.display = 'block';
    profitabilityPercentageChartEle.parentNode.style.display = 'block';
    monthlyExpensesChartEle.parentNode.style.display = 'block';
    // operations loaders
    newAssetsFromExistingClientsChartEle.parentNode.style.display = 'block';
    daysToIssueChartEle.parentNode.style.display = 'block';
    reviewPreparedChartEle.parentNode.style.display = 'block';
}, 1000);


function firstLetterUpperCase(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function fetchLeadsGeneratedChartData() {
    const data = new URLSearchParams({
        report_type: 'leads-generated',
        company_id: companyId
    });

    fetch(`${appUrl}/admin/company-reports-data?${data}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(response => {
            const leadsData = response?.data;
            leadsGeneratedChart.data.datasets = [];
            for (const key in leadsData) {
                if (key !== "current_year_target") {
                    leadsGeneratedChart.data.datasets.push({
                        label: key,
                        data: leadsData[key].map((x) => x.count),
                    });
                }
                if (key === "current_year_target") {
                    leadsGeneratedChart.data.datasets.push({
                        label: "CY Target",
                        type: "line",
                        data: leadsData[key].map((x) => x.value),
                    });
                }
            }
            leadsGeneratedChart.update();
            leadsGeneratedLoader.style.display = 'none';
            leadsGeneratedChartEle.parentNode.style.display = 'block';
        })
        .catch((error) => {
            toastr.error(error?.message);
        });
}

function fetchOpportunityChartData() {
    const data = new URLSearchParams({
        report_type: 'opportunities',
        company_id: companyId
    });

    fetch(`${appUrl}/admin/company-reports-data?${data}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(response => {
            const opportunityData = response?.data;
            openOpportunitiesChart.data.datasets = [];
            for (const key in opportunityData) {
                if (key !== "current_year_target") {
                    openOpportunitiesChart.data.datasets.push({
                        label: key,
                        data: opportunityData[key].map((x) => x.count),
                    });
                }
                if (key === "current_year_target") {
                    openOpportunitiesChart.data.datasets.push({
                        label: "CY Target",
                        type: "line",
                        data: opportunityData[key].map((x) => x.value),
                    });
                }
            }
            openOpportunitiesChart.update();
            openOpportunitiesLoader.style.display = 'none';
            openOpportunitiesChartEle.parentNode.style.display = 'block';
        })
        .catch((error) => {
            toastr.error(error?.message);
        });
}

function fetchCashOnHandChartData() {
    const data = new URLSearchParams({
        report_type: 'cash-on-hand',
        company_id: companyId
    });

    fetch(`${appUrl}/admin/company-reports-data?${data}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(response => {
            const cashOnHandData = response?.data;
            cashOnHandChart.data.datasets = [
                {
                    label: cashOnHandData[0].year,
                    name: cashOnHandData[0].year,
                    data: cashOnHandData.map((x) => x.amount),
                }
            ];
            cashOnHandChart.update();
            cashOnHandLoader.style.display = 'none';
            cashOnHandChartEle.parentNode.style.display = 'block';
        })
        .catch((error) => {
            toastr.error(error?.message);
        });
}

function fetchCurrentLiabilitiesChartData() {
    const data = new URLSearchParams({
        report_type: 'current-liabilities',
        company_id: companyId
    });

    fetch(`${appUrl}/admin/company-reports-data?${data}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(response => {
            const currentLiabilitiesData = response?.data;
            currentLiabilitiesChart.data.datasets = [];

            for (const key in currentLiabilitiesData) {
                currentLiabilitiesChart.data.datasets.push({
                    label: key,
                    name: key,
                    type: "line",
                    data: currentLiabilitiesData[key].map(x => x.amount),
                });
            }

            currentLiabilitiesChart.update();
            currentLiabilitiesLoader.style.display = 'none';
            currentLiabilitiesChartEle.parentNode.style.display = 'block';
        })
        .catch((error) => {
            toastr.error(error?.message);
        });
}

function fetchMonthlyExpensesChartData() {
    const data = new URLSearchParams({
        report_type: 'monthly-expenses',
        company_id: companyId
    });

    fetch(`${appUrl}/admin/company-reports-data?${data}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(response => {
            const monthlyExpenseData = response?.data;
            monthlyExpensesChart.data.datasets = [];

            for (const key in monthlyExpenseData) {
                if (key === "expenses") {
                    const yearlyExpenses = monthlyExpenseData[key];
                    for (const expenseKey in yearlyExpenses) {
                        monthlyExpensesChart.data.datasets.push({
                            name: yearlyExpenses[expenseKey][0].year,
                            label: yearlyExpenses[expenseKey][0].year,
                            data: yearlyExpenses[expenseKey].map(x => x.amount),
                        });
                        if (yearlyExpenses[expenseKey][0]?.currency) {
                            currency = yearlyExpenses[expenseKey][0]?.currency;
                        }
                    }
                }

                if (key === "budget") {
                    monthlyExpensesChart.data.datasets.push({
                        name: firstLetterUpperCase(key),
                        label: firstLetterUpperCase(key),
                        type: "line",
                        data: monthlyExpenseData[key].map(x => x.amount),
                    });
                }
            }

            monthlyExpensesChart.update();
            monthlyExpensesLoader.style.display = 'none';
            monthlyExpensesChartEle.parentNode.style.display = 'block';
        })
        .catch((error) => {
            toastr.error(error?.message);
        });
}

function fetchProfitabilityPercentageChartData() {
    const data = new URLSearchParams({
        report_type: 'profitability-percentage',
        company_id: companyId
    });

    fetch(`${appUrl}/admin/company-reports-data?${data}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(response => {
            const profitabilityPercentageData = response?.data;
            profitabilityPercentageChart.data.datasets = [];

            for (const key in profitabilityPercentageData) {
                if (key === "goal") {
                    profitabilityPercentageChart.data.datasets.push({
                        label: firstLetterUpperCase(key),
                        type: "line",
                        data: profitabilityPercentageData[key]?.map(x => x.value),
                    });
                } else {
                    profitabilityPercentageChart.data.datasets.push({
                        label: key,
                        data: profitabilityPercentageData[key]?.map(x => x.percentage),
                    });
                }
            }

            profitabilityPercentageChart.update();
            profitabilityPercentageLoader.style.display = 'none';
            profitabilityPercentageChartEle.parentNode.style.display = 'block';
        })
        .catch((error) => {
            toastr.error(error?.message);
        });
}

fetchLeadsGeneratedChartData();
fetchOpportunityChartData();
fetchCashOnHandChartData();
fetchCurrentLiabilitiesChartData();
fetchMonthlyExpensesChartData();
fetchProfitabilityPercentageChartData();