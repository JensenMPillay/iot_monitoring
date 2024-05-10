export enum EventType {
  STATUS_CHANGED = "status_changed",
  VALUE_CHANGED = "value_changed",
}

type Log = {
  id: string;
  event: string;
  type: EventType;
  value: string;
  createdAt: Date;
  sensor: string;
};

export default Log;
