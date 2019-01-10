import {Lecturer} from "./lecturer";
import {Schedule} from "./schedule";
import {LectureType} from "./lecture-type";

export class Lecture {
    id: number;
    lecturer: Lecturer;
    hours: number;
    comments: string;
    schedules: Schedule[];
    lectureType: LectureType;

    constructor(
        id: number,
        lecturer: Lecturer,
        hours: number,
        comments: string,
        schedules: Schedule[],
        lectureType: LectureType
    ) {
        this.id = id;
        this.lecturer = lecturer;
        this.hours = hours;
        this.comments = comments;
        this.schedules = schedules;
        this.lectureType = lectureType;
    }
}
