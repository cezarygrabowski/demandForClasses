import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Week} from '../_models/week';
import {Building} from '../_interfaces/building';
import {FormBuilder, FormGroup} from '@angular/forms';
import {Room} from '../_interfaces/room';
import {Schedule} from '../_models/schedule';
import {Lecture} from '../_models/lecture';

@Component({
    selector: 'app-place-form',
    templateUrl: './place-form.component.html',
    styleUrls: ['./place-form.component.css']
})
export class PlaceFormComponent implements OnInit {
    weekLabel: string;
    building: Building;
    room: Room;
    placeForm: FormGroup;
    rooms: Room[] = [];
    lectureSchedule: Schedule;
    @Input('week') week: Week;
    @Input('lecture') lecture: Lecture;
    @Input('buildings') buildings: Building[];

    @Output() scheduleEmitter: EventEmitter<Schedule> = new EventEmitter();
    constructor(private formBuilder: FormBuilder) {}

    ngOnInit() {
        this.building = null;
        this.room = null;
        this.findScheduleByWeek();
        this.initForm();
        this.weekLabel = Week.WEEK_TRANSLATION[this.week.week];
    }

    private initForm() {
        this.placeForm = this.formBuilder.group({
            building: [this.lectureSchedule ? this.lectureSchedule.building : ''],
            room: [this.lectureSchedule ? this.lectureSchedule.room : ''],
        });

        this.onChanges();
    }

    private onChanges() {
        this.placeForm.get('building').valueChanges.subscribe((buildingNumber: string) => {
            this.getBuildingFromForm(buildingNumber);
            this.placeForm.get('room').setValue(null);
        });

        this.placeForm.get('room').valueChanges.subscribe((roomNumber: string) => {
            this.getRoomFromForm(roomNumber);
        });
    }

    private getBuildingFromForm(buildingNumber: string): void {
        this.building = this.buildings.filter((building: Building) => {
            return building.name === buildingNumber;
        })[0];

        this.emitSchedule();
        this.rooms = this.building.rooms;
    }

    private getRoomFromForm(roomNumber: string): void {
        this.room = this.rooms.filter((room: Room) => {
            return room.name === roomNumber;
        })[0];
        this.emitSchedule();
        //user chose a room that doesn't exist
        //code below causes infinite loop; figure out a way to prevent user from choosing a room that doesn't exist
        // if (this.room == null) {
        //     this.placeForm.get('room').setValue(null);
        // }
    }

    private emitSchedule()
    {
        const weekNumber = this.week.week.replace( /^\D+/g, '');
        const schedule = new Schedule(null, weekNumber, this.week.hours, this.building ? this.building.id : null, this.room ? this.room.id : null, this.lecture);
        this.scheduleEmitter.emit(schedule);
    }

    private findScheduleByWeek()
    {
        this.lectureSchedule = this.lecture.schedules.filter((schedule: Schedule) => {
            console.log(schedule);
            console.log(this.week);
            return schedule.weekNumber === this.week.week;
        })[0];
    }
}
