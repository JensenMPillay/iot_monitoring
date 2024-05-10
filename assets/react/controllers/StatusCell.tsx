import { cn } from "@/lib/utils";
import React from "react";

function StatusCell({ status }: { status: boolean }): React.JSX.Element {
  return (
    <div
      className={cn(
        "size-4 rounded-full shadow-2xl m-auto",
        status
          ? "bg-green-400 shadow-green-200"
          : "bg-red-700 shadow-red-500 animate-blink"
      )}
    />
  );
}

export default StatusCell;
