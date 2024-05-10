import { buttonVariants } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  Table,
  TableBody,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { cn } from "@/lib/utils";
import Module from "@/types/Module";
import { format } from "date-fns";
import React from "react";
import SensorRow from "./SensorRow";

export default function ModuleCard({
  module,
}: {
  module: Module;
}): React.JSX.Element {
  const { id, name, sensors, createdAt, updatedAt } = module;

  return (
    <Card>
      <CardHeader className="flex flex-row justify-between items-center">
        <div className="flex flex-col">
          <CardTitle>Module {name.toUpperCase()}</CardTitle>
          <CardDescription>
            Sensors activity from your IoT module.
          </CardDescription>
        </div>
        <div className="flex flex-row space-x-2">
          <a
            href={`${window.location.origin}/edit/${module.id}`}
            className={cn(buttonVariants({ variant: "outline" }))}
          >
            Edit
          </a>
          <a
            href={`${window.location.origin}/delete/${module.id}`}
            className={cn(buttonVariants({ variant: "destructive" }))}
          >
            Delete
          </a>
        </div>
      </CardHeader>
      <CardContent>
        <Table>
          <TableHeader>
            <TableRow className="dark:hover:bg-zinc-950">
              <TableHead>Name</TableHead>
              <TableHead className="text-center">Status</TableHead>
              <TableHead className="hidden sm:table-cell text-center">
                Type
              </TableHead>
              <TableHead className="hidden sm:table-cell text-center">
                Value
              </TableHead>
              <TableHead className="hidden sm:table-cell text-center">
                Unit
              </TableHead>
              <TableHead className="hidden sm:table-cell text-center">
                Uptime
              </TableHead>
              <TableHead className="hidden sm:table-cell text-center">
                Data Sent Count
              </TableHead>
              <TableHead className="hidden md:table-cell text-right">
                Last Update
              </TableHead>
              <TableHead className="hidden md:table-cell text-center">
                Logs
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {sensors.map((sensor, index) => (
              <SensorRow key={index} sensor={sensor} />
            ))}
          </TableBody>
        </Table>
      </CardContent>
      <CardFooter className="flex flex-row text-xs justify-between items-center">
        <span>Created At : {format(createdAt, "eee d MMM H:mm:ss")}</span>
        {updatedAt && (
          <span>Updated At : {format(updatedAt, "eee d MMM H:mm:ss")}</span>
        )}
      </CardFooter>
    </Card>
  );
}
