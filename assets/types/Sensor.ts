import Log from "./Log";

export enum SensorType {
  TEMPERATURE = "temperature",
  HUMIDITY = "humidity",
  LIGHT = "light",
  SPEED = "speed",
  NOISE = "noise",
}

type Sensor = {
  id: string;
  name: string;
  type: SensorType;
  value: string;
  unit: string;
  uptime: Date | null;
  dataSentCount: number | null;
  status: boolean;
  createdAt: Date;
  updatedAt: Date | null;
  logs: Log[];
  module: string;
};

export default Sensor;
