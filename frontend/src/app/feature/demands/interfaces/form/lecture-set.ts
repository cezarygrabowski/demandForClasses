import {Lecturer} from "./lecturer";

export interface LectureSet {
  lecturer: Lecturer;
  notes: string;
  allocatedWeeks: [];
  hoursToDistribute: number;
  type: string;
  uuid: string;
}
