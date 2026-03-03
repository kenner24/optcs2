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

function SeminarResponseRatesChart() {
  const { data: semiRespRates } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.LEADS_GENERATED_CHART,
    year: moment().format("YYYY"),
  },{
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
        text: "Seminar Response Rates",
      },
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
    for (const key in semiRespRates?.data) {
      if (key !== "current_year_target") {
        newDataSet.push({
          label: key,
          data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
        });
      }
      if (key === "current_year_target") {
        newDataSet.push({
          label: "CY Target",
          type: "line",
          data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
        });
      }
    }
    setChartData({
      ...chartData,
      datasets: newDataSet
    });
    setLoadingState(false);
  }, [semiRespRates]);

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

export default SeminarResponseRatesChart;
