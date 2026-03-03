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
} from "chart.js";
import { Bar } from "react-chartjs-2";
import moment from "moment";
import { useGetOverviewPageDataQuery } from "../../redux/api/overview/overview-api";

// Initialize the required modules
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);


function OpportunityClosedPerMonthChart() {
  const { data } = useGetOverviewPageDataQuery(moment().format("YYYY"));

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
        display: false,
        text: "Opportunities Closed per Month (Won/Lost)",
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
    if (data?.success) {
      const tempData = data?.data?.opportunity_closed_per_month_won_lost;

      setChartData({
        ...chartData,
        datasets: [
          {
            label: "Won",
            data: tempData?.won?.map(x => x.count),
          },
          {
            label: "Lost",
            data: tempData?.lost?.map(x => x.count),
          }
        ]
      });
      setLoadingState(false);
    }
  }, [data]);

  return (
    <div>
      {
        loading ?
          <Spinner animation="border" /> :
          <Bar
            options={options}
            data={chartData}
          />
      }
    </div>
  );
}

export default OpportunityClosedPerMonthChart;
