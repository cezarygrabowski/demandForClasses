import {LectureSet} from "./lecture-set";

export enum DemandStatus {
  STATUS_UNTOUCHED = 0,
  STATUS_ASSIGNED_BY_DEPARTMENT_MANAGER = 1,
  STATUS_ACCEPTED_BY_TEACHER = 2,
  STATUS_ACCEPTED_BY_DEPARTMENT_MANAGER = 3,
  STATUS_ACCEPTED_BY_INSTITUTE_DIRECTOR = 4,
  STATUS_ACCEPTED_BY_DEAN = 5,
  STATUS_EXPORTED = 6,
  DECLINED_BY_TEACHER = 7
}
export interface Demand {
  schoolYear: string;
  department: string;
  groupName: string;
  groupType: string;
  semester: string;
  subjectName: string;
  institute: string;
  subjectShortName: string;
  subjectTotalHours: string;
  uuid: string;
  lectureSets: LectureSet[];
  status: number;
}
