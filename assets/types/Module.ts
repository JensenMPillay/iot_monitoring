import Sensor from "./Sensor";

type Module = {
  id: string;
  name: string;
  createdAt: Date;
  updatedAt: Date | null;
  sensors: Sensor[];
};

export default Module;
