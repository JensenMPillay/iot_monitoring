import Module from "@/types/Module";
import React, { createContext } from "react";

export const DashboardContext: React.Context<Module[] | null> = createContext<
  Module[] | null
>(null);
