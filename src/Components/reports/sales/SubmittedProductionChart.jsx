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
  Colors,
} from "chart.js";
import { Line } from "react-chartjs-2";
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
  Legend,
  Colors
);

function SubmittedProductionChart() {
  const { data: SubProdData } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.LEADS_GENERATED_CHART,
    year: moment().format("YYYY"),
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
        text: "Submitted Production",
        // position: "left"
      },
    },
    redraw: true,
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
    for (const key in SubProdData?.data) {
      if (key !== "current_year_target") {
        newDataSet.push({
          label: key,
          // data: leadsData.data[key].map((x) => x.count),
          data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12))
        });
      }
      if (key === "current_year_target") {
        newDataSet.push({
          label: "CY Target",
          // data: Array(12).fill(leadsData.data[key]),
          data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12))
        });
      }
    }

    setChartData({
      ...chartData,
      datasets: newDataSet
    });
    setLoadingState(false);
  }, [SubProdData]);

  return (
    <div>
      {
        loading ?
          <Spinner animation="border" /> :
          <Line data={chartData} options={options} />
      }
    </div>
  );
}

export default SubmittedProductionChart;
