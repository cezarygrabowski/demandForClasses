import {LectureSet} from "./lecture-set";

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
  lectureSets: LectureSet[]
}
