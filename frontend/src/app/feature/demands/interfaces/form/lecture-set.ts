import {Lecturer} from "./lecturer";

export interface LectureSet {
  lecturer: Lecturer;
  notes: string;
  allocatedWeeks: {
    allocatedHours: any,
    room: any,
    building: any
  }[];
  hoursToDistribute: number;
  type: string;
  uuid: string;
}
