import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/components/ui/collapsible";
import { TableCell, TableRow } from "@/components/ui/table";
import Sensor from "@/types/Sensor";
import { format } from "date-fns";
import { ChevronDown } from "lucide-react";
import React, { useEffect, useState } from "react";
import { toast } from "sonner";
import LogsChart from "./LogsChart";
import StatusCell from "./StatusCell";
import UptimeCell from "./UptimeCell";

export default function SensorRow({
  sensor,
}: {
  sensor: Sensor;
}): React.JSX.Element {
  const {
    id,
    name,
    type,
    value,
    unit,
    uptime,
    dataSentCount,
    status,
    updatedAt,
    logs,
    module,
  } = sensor;

  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (!status)
      toast.error("❌ SENSOR DOWN !", {
        description: `The Sensor ${name
          .replace("_", " ")
          .toUpperCase()} is DOWN !`,
      });
  }, [status]);

  return (
    <Collapsible asChild>
      <>
        <TableRow className="font-mono">
          <TableCell className="font-medium capitalize">
            {name.replace("_", " ")}
          </TableCell>
          <TableCell className="my-auto">
            <StatusCell status={status} />
          </TableCell>
          <TableCell className="hidden sm:table-cell text-center capitalize text-zinc-400">
            {type}
          </TableCell>
          <TableCell className="hidden sm:table-cell text-center font-semibold">
            {value}
          </TableCell>
          <TableCell className="hidden sm:table-cell text-center text-zinc-400">
            {unit}
          </TableCell>
          <TableCell className="hidden sm:table-cell text-center">
            {uptime && <UptimeCell uptime={uptime} />}
          </TableCell>
          <TableCell className="hidden sm:table-cell text-center text-zinc-400">
            {dataSentCount}
          </TableCell>
          <TableCell className="hidden md:table-cell text-right text-zinc-400">
            {updatedAt && format(updatedAt, "eee d MMM H:mm:ss")}
          </TableCell>
          <TableCell className="hidden md:table-cell text-center">
            <CollapsibleTrigger asChild>
              <ChevronDown className="mx-auto cursor-pointer size-4 shrink-0 transition-all duration-200 data-[state=open]:rotate-180 hover:underline" />
            </CollapsibleTrigger>
          </TableCell>
        </TableRow>
        <CollapsibleContent asChild>
          <TableRow className="w-full">
            <TableCell colSpan={9} aria-colspan={9} className="text-center">
              {logs.length > 0 ? (
                <LogsChart logs={logs} />
              ) : (
                "No Logs available."
              )}
            </TableCell>
          </TableRow>
        </CollapsibleContent>
      </>
    </Collapsible>
  );
}
