import {Subject} from '../_interfaces/subject';
import {Lecture} from '../_interfaces/lecture';

export interface DemandElement {
    subject: Subject;
    department: string;
    group: string;
    groupTyoe: string;
    id: number;
    institute: string;
    lectures: Lecture[];
    semester: string;
    status: string;
    totalHours: number;
    yearNumber: string;
}

