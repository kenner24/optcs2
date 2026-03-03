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
import { firstLetterUpperCase } from "../../../helper/helper";
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

function MonthlyExpensesChart({ selectedYear }) {
  const [currency, setCurrency] = useState("USD");
  const { data: monthlyExpense } = useGetReportChartDataQuery({
    report_type: ChartTypeConstant.MONTHLY_EXPENSES,
    year: selectedYear ?? moment().format("YYYY")
  }, {
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
        text: "Monthly Expenses",
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
    if (monthlyExpense?.success && Object.keys(monthlyExpense?.data).length > 0) {
      for (const key in monthlyExpense?.data) {
        if (key === "expenses") {
          const yearlyExpenses = monthlyExpense?.data[key];
          for (const expenseKey in yearlyExpenses) {
            newDataSet.push({
              name: yearlyExpenses[expenseKey][0].year,
              label: yearlyExpenses[expenseKey][0].year,
              data: yearlyExpenses[expenseKey].map(x => x.amount),
            });
            if (yearlyExpenses[expenseKey][0]?.currency !== null) {
              setCurrency(yearlyExpenses[expenseKey][0]?.currency);
            }
          }
        } else {
          newDataSet.push({
            name: firstLetterUpperCase(key),
            label: firstLetterUpperCase(key),
            type: "line",
            data: monthlyExpense.data[key].map(x => x.amount),
          });
        }
      }
      setChartData({
        ...chartData,
        datasets: newDataSet
      });
    }
    setLoadingState(false);
  }, [monthlyExpense]);

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

export default MonthlyExpensesChart;
