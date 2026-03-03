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
  Legend,
  Colors
);

function OpenLeadsPerFunnelChart() {
  const { data: openLeadsData } = useGetReportChartDataQuery({
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
        text: "Open Leads Per Funnel",
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
        label: "Direct Mail",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "TV",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "Radio",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "Social",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "Referral",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "Workshops",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
      {
        label: "Average",
        type: "line",
        borderDash: [5, 5],
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
      },
    ],
  });
  const [loading, setLoadingState] = useState(true);

  useEffect(() => {
    const newDataSet = [];
    // for (const key in openLeadsData?.data) {
    //   if (key !== "current_year_target") {
    //     newDataSet.push({
    //       name: key,
    //       data: openLeadsData.data[key].map((x) => x.count),
    //     });
    //   }
    //   if (key === "current_year_target") {
    //     newDataSet.push({
    //       name: "CY Target",
    //       type: "line",
    //       data: Array(12).fill(openLeadsData.data[key]),
    //     });
    //   }
    // }
    // setChartData({
    //   ...chartData,
    //   datasets: newDataSet
    // });
    setLoadingState(false);
  }, [openLeadsData]);

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

export default OpenLeadsPerFunnelChart;
