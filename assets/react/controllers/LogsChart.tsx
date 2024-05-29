import Log from "@/types/Log";
import {
  ChartData,
  Chart as ChartJS,
  ChartOptions,
  Legend,
  LinearScale,
  LineElement,
  PointElement,
  TimeScale,
  Title,
  Tooltip,
} from "chart.js";
import "chartjs-adapter-date-fns";
import { differenceInHours } from "date-fns";
import React from "react";
import { Line } from "react-chartjs-2";

export default function LogsChart({
  logs,
}: {
  logs: Log[];
}): React.JSX.Element {
  ChartJS.register(
    TimeScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
  );

  const options = {
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
      title: {
        display: true,
        text: "Logs",
      },
      colors: {
        enabled: true,
      },
    },
    scales: {
      x: {
        type: "time",
        time: {
          unit: "minute",
          displayFormats: {
            day: "EEE dd MM",
            hour: "H:mm:ss",
            min: "H:mm:ss",
          },
        },
        adapters: {
          date: {},
        },
      },
    },
  } satisfies ChartOptions;

  const lastLogs = logs.filter(
    (log) => differenceInHours(new Date(), log.createdAt) < 1
  );

  const data = {
    labels: lastLogs.map((log) => log.createdAt),
    datasets: [
      {
        label: "Value",
        data: lastLogs.map((log) => Number(log.value)),
        borderColor: "rgb(255, 255, 255)",
        backgroundColor: "rgb(255, 255, 255)",
      },
    ],
  } satisfies ChartData;

  return <Line options={options} data={data} />;
}
