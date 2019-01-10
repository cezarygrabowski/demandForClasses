import {Schedule} from './schedule';
import {Lecturer} from './lecturer';
import {LectureType} from './lecture-type';

export interface Lecture {
    id: string;
    lecturer: Lecturer;
    hours: number;
    comments: string;
    schedules: Schedule[];
    lectureType: LectureType;
}

