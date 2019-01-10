export class Schedule {
    id: number|null;
    weekNumber: string;
    suggestedHours: number;
    building: number|null;
    room: number|null;

    constructor(
        id: number|null,
        weekNumber: string,
        suggestedHours: number,
        building: number|null,
        room: number|null
    ) {
        this.id = id;
        this.weekNumber = weekNumber;
        this.suggestedHours = suggestedHours;
        this.building = building;
        this.room = room;
    }
}
