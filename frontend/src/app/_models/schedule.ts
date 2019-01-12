import {Lecture} from "./lecture";

export class Schedule {
    id: number|null;
    weekNumber: string;
    suggestedHours: number;
    building: number|null;
    room: number|null;
    lecture: Lecture;
    constructor(
        id: number|null,
        weekNumber: string,
        suggestedHours: number,
        building: number|null,
        room: number|null,
        lecture: Lecture
    ) {
        this.id = id;
        this.weekNumber = weekNumber;
        this.suggestedHours = suggestedHours;
        this.building = building;
        this.room = room;
        this.lecture = lecture;
    }
}
