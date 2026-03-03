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

function ReviewPreparedChart() {
  const { data: reviewPrepData } = useGetReportChartDataQuery({
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
        text: "Review Prepared",
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
    datasets: [
      {
        label: "Review",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "2023",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "YTD 2022",
        type: "line",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "YTD 2023",
        type: "line",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
    ],
  });
  const [loading, setLoadingState] = useState(true);

  useEffect(() => {
    const newDataSet = [];
    // for (const key in reviewPrepData?.data) {
    //   if (key !== "current_year_target") {
    //     newDataSet.push({
    //       name: key,
    //       data: reviewPrepData.data[key].map((x) => x.count),
    //     });
    //   }
    //   if (key === "current_year_target") {
    //     newDataSet.push({
    //       name: "CY Target",
    //       type: "line",
    //       data: Array(12).fill(reviewPrepData.data[key]),
    //     });
    //   }
    // }
    // setChartData({
    //   ...chartData,
    //   datasets: newDataSet
    // });
    setLoadingState(false);
  }, [reviewPrepData]);

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

export default ReviewPreparedChart;
