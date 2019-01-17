import {Subject} from '../_interfaces/subject';
import {Lecture} from '../_models/lecture';

export interface DemandElement {
    subject: Subject;
    department: string;
    group: string;
    groupType: string;
    id: number;
    institute: string;
    lectures: Lecture[];
    semester: string;
    status: string;
    totalHours: number;
    yearNumber: string;
}

