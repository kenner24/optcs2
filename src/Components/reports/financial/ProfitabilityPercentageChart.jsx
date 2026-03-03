import React, { useEffect, useState } from "react";
import Spinner from "react-bootstrap/Spinner";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  PointElement,
  LineElement,
} from "chart.js";
import { Bar } from "react-chartjs-2";
import { useGetReportChartDataQuery } from "../../../redux/api/report/report-api";
import { ChartTypeConstant } from "../../../constants/chartType.constant";
import { firstLetterUpperCase } from "../../../helper/helper";
import moment from "moment";

// Initialize the required modules
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

function ProfitabilityPercentageChart({ selectedYear }) {
  const { data: profitPct } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.PROFITABILITY_PERCENTAGE,
    year: selectedYear ?? moment().format("YYYY")
  }, {
    refetchOnMountOrArgChange: true
  });
  const options = {
    responsive: true,
    interaction: {
      mode: "index",
      intersect: false,
    },
    plugins: {
      legend: {
        // position: "left",
      },
      title: {
        display: true,
        text: "Profitability %",
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            return `${context.dataset.label}: ${context.parsed.y} %`;
          }
        }
      }
    },
  };

  const [chartData, setChartData] = useState({
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
    datasets: [],
  });
  const [loading, setLoadingState] = useState(true);

  useEffect(() => {
    const newDataSet = [];
    for (const key in profitPct?.data) {
      if (key === "goal") {
        newDataSet.push({
          label: firstLetterUpperCase(key),
          type: "line",
          data: profitPct?.data[key]?.map(x => x.value),
        });
      } else {
        newDataSet.push({
          label: key,
          data: profitPct?.data[key]?.map(x => x.percentage),
        });
      }
    }
    setChartData({
      ...chartData,
      datasets: newDataSet
    });
    setLoadingState(false);
  }, [profitPct]);

  return (
    <div>
      {
        loading ?
          <Spinner animation="border" /> :
          <Bar options={options} data={chartData} />
      }
    </div>
  );
}

export default ProfitabilityPercentageChart;
