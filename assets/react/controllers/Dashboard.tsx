import { buttonVariants } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { DashboardContext } from "@/context/Contexts";
import { cn } from "@/lib/utils";
import { CirclePlusIcon } from "lucide-react";
import React, { useContext } from "react";
import ModuleCard from "./ModuleCard";

export default function Dashboard(): React.JSX.Element {
  const data = useContext(DashboardContext);
  return (
    <Card className="flex-1">
      <CardHeader className="flex flex-row justify-between items-center">
        <div className="flex flex-col">
          <CardTitle>Modules</CardTitle>
          <CardDescription>Activity from your IoT modules.</CardDescription>
        </div>
        <a
          href={`${window.location.origin}/create`}
          className={cn(buttonVariants({ variant: "default", size: "icon" }))}
        >
          <CirclePlusIcon />
        </a>
      </CardHeader>
      <CardContent className="space-y-2 sm:space-y-4 md:space-y-6">
        {data &&
          data.map((module, index) => (
            <ModuleCard key={index} module={module} />
          ))}
      </CardContent>
    </Card>
  );
}
