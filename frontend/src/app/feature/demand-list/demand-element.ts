import {Subject} from '../../shared/_interfaces/subject';
import {Lecture} from '../../shared/_models/lecture';

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

