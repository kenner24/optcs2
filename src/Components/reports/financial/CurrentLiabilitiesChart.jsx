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
  Legend
);

function CurrentLiabilitiesChart({ selectedYear }) {
  const [currency, setCurrency] = useState("USD");
  const { data: currentLiabilities } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.CURRENT_LIABILITIES,
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
        text: "Current Liabilities",
      },
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
    if (currentLiabilities?.success && Object.keys(currentLiabilities?.data).length > 0) {
      for (const key in currentLiabilities?.data) {
        newDataSet.push({
          label: key,
          name: key,
          type: "line",
          data: currentLiabilities.data[key].map(x => x.amount),
        });
        if(currentLiabilities.data[key][0]?.currency !== null){
          setCurrency(currentLiabilities.data[key][0]?.currency);
        }
      }

      setChartData({
        ...chartData,
        datasets: newDataSet
      });
    }
    setLoadingState(false);
  }, [currentLiabilities]);

  return (
    <div>
      {
        loading ?
          <Spinner animation="border" /> :
          <Line options={options} data={chartData} />
      }
    </div>
  );
}

export default CurrentLiabilitiesChart;
