import { Toaster } from "@/components/ui/sonner";
import { DashboardContext } from "@/context/Contexts";
import Module from "@/types/Module";
import React, { useEffect, useState } from "react";
import Dashboard from "./Dashboard";

export default function App(props: {
  data: string;
  url: string;
}): React.JSX.Element {
  const [data, setData] = useState<Module[]>(JSON.parse(props.data));

  useEffect(() => {
    function syncData() {
      const eventSource = new EventSource(props.url);
      eventSource.onmessage = (event) => {
        setData(JSON.parse(event.data));
      };
    }
    syncData();
  }, []);

  return (
    <DashboardContext.Provider value={data}>
      <Dashboard />
      <Toaster richColors theme="dark" expand={true} />
    </DashboardContext.Provider>
  );
}
