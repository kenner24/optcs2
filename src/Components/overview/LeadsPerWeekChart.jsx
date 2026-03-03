import React, { useEffect, useState } from "react";
import Spinner from "react-bootstrap/Spinner";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  PointElement,
  LineElement,
  Colors,
} from "chart.js";
import { Line } from "react-chartjs-2";
import moment from "moment";
import { useGetOverviewPageDataQuery } from "../../redux/api/overview/overview-api";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Colors
);

function LeadsPerWeekChart() {
  const { data } = useGetOverviewPageDataQuery(moment().format("YYYY"));
  const options = {
    responsive: true,
    interaction: {
      mode: "index",
      intersect: false,
    },
    plugins: {
      title: {
        display: false,
        text: "Leads Converted per Week",
      },
    },
    redraw: true,
  };

  const [loading, setLoadingState] = useState(true);
  const [chartData, setChartData] = useState({
    labels: [],
    datasets: [],
  });

  useEffect(() => {
    if (data?.success) {
      const tempData = data?.data?.leads_per_week;
      const newDataSet = [];
      const labels = [];

      if (tempData) {
        tempData.forEach(element => {
          labels.push(`Week ${element?.week}`);
          newDataSet.push(element?.total);
        });
        setChartData({
          ...chartData,
          labels,
          datasets: [
            {
              label: "Leads",
              data: newDataSet
            }
          ]
        });
      }
      setLoadingState(false);
    }
  }, [data]);

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

export default LeadsPerWeekChart;
