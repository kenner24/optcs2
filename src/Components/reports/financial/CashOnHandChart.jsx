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

function CashOnHandChart({ selectedYear }) {
  const [currency, setCurrency] = useState("USD");
  const { data: cashOnHand } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.CASH_ON_HAND,
    year: selectedYear ?? moment().format("YYYY")
  },{
    refetchOnMountOrArgChange: true,
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
        text: "Cash On Hand",
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
    if (cashOnHand?.data.length > 0) {
      if (cashOnHand?.data[0].currency !== null) {
        setCurrency(cashOnHand?.data[0].currency);
      }
      setChartData({
        ...chartData,
        datasets: [
          {
            label: cashOnHand?.data[0].year,
            name: cashOnHand?.data[0].year,
            data: cashOnHand.data.map((x) => x.amount),
          }
        ]
      });
    }
    setLoadingState(false);
  }, [cashOnHand]);

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

export default CashOnHandChart;
