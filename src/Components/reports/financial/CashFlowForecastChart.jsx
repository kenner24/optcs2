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

function CashFlowForecastChart({ selectedYear }) {
  const [currency, setCurrency] = useState("USD");
  const { data: cashFlowForecast } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.LEADS_GENERATED_CHART,
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
        text: "Cash Flow Forecast",
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
  const skipped = (ctx, value) => ctx.p0.skip || ctx.p1.skip ? value : undefined;
  const down = (ctx, value) => ctx.p0.parsed.y > ctx.p1.parsed.y ? value : undefined;
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
        label: "2023",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
        segment: {
          borderColor: ctx => skipped(ctx, "rgb(0,0,0,0.2)") || down(ctx, "rgb(192,75,75)"),
          borderDash: ctx => skipped(ctx, [6, 6]),
        },
        spanGaps: true
      },
      {
        label: "Forecast",
        data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 12)),
        segment: {
          borderColor: ctx => skipped(ctx, "rgb(0,0,0,0.2)") || down(ctx, "rgb(192,75,75)"),
          borderDash: ctx => skipped(ctx, [6, 6]),
        },
        spanGaps: true
      }
    ],
  });
  const [loading, setLoadingState] = useState(true);

  useEffect(() => {
    const newDataSet = [];
    // for (const key in leadsData?.data) {
    //   if (key !== "current_year_target") {
    //     newDataSet.push({
    //       name: key,
    //       data: leadsData.data[key].map((x) => x.count),
    //     });
    //   }
    //   if (key === "current_year_target") {
    //     newDataSet.push({
    //       name: "CY Target",
    //       type: "line",
    //       data: Array(12).fill(leadsData.data[key]),
    //     });
    //   }
    // }
    // setChartData({
    //   ...chartData,
    //   datasets: newDataSet
    // });
    setLoadingState(false);
  }, [cashFlowForecast]);

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

export default CashFlowForecastChart;
