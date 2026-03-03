import React, { useEffect, useState } from "react";
import Spinner from "react-bootstrap/Spinner";
import {
  Chart as ChartJS,
  Tooltip,
  Legend,
  ArcElement,
} from "chart.js";
import { Pie } from "react-chartjs-2";
import moment from "moment";
import { useGetOverviewPageDataQuery } from "../../redux/api/overview/overview-api";

ChartJS.register(
  ArcElement,
  Tooltip,
  Legend
);


function OpportunityByStageChart() {
  const { data } = useGetOverviewPageDataQuery(moment().format("YYYY"));
  const [loading, setLoadingState] = useState(true);
  const [chartData, setChartData] = useState({
    labels: [],
    datasets: [
      {
        label: "Count",
        data: [],
      },
    ],
  });
  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: "left",
      },
      title: {
        display: false,
        text: "Opportunity by Stage",
      },
    },
  };
  useEffect(() => {
    if (data?.success) {
      const stageData = data?.data?.opportunity_by_stage;
      if (stageData) {
        const labels = [];
        const tempData = [];
        stageData.forEach(element => {
          labels.push(element?.stage);
          tempData.push(element?.count);
        });

        setChartData({
          ...chartData,
          labels,
          datasets: [{
            label: "Count",
            data: tempData,
          }]
        });
      }
      setLoadingState(false);
    }
  }, [data])

  return (
    <div>
      {
        loading ?
          <Spinner animation="border" /> :
          <Pie options={options} data={chartData} />
      }
    </div>
  );
}

export default OpportunityByStageChart;
