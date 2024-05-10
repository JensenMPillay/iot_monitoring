import { differenceInDays, differenceInMilliseconds } from "date-fns";
import React, { useEffect, useState } from "react";

export default function UptimeCell({
  uptime,
}: {
  uptime: Date;
}): React.JSX.Element | null {
  const [uptimeDiff, setUptimeDiff] = useState<{
    days: number;
    time: Date;
  } | null>(null);

  useEffect(() => {
    const intervalId = setInterval(() => {
      setUptimeDiff({
        days: differenceInDays(new Date(), uptime),
        time: new Date(
          differenceInMilliseconds(new Date(), uptime) - 3600 * 1000
        ),
      });
    }, 1000);

    return () => clearInterval(intervalId);
  }, []);

  return (
    uptimeDiff && (
      <div className="font-semibold font-mono">
        {uptimeDiff.days !== 0 && <span>{uptimeDiff.days} days, </span>}
        <span>{uptimeDiff.time.toLocaleTimeString()}</span>
      </div>
    )
  );
}
