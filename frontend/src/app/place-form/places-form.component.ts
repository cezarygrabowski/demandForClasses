import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Schedule} from '../_models/schedule';
import {Lecture} from '../_models/lecture';
import {Building} from '../_interfaces/building';
import {MatAutocompleteSelectedEvent} from '@angular/material';
import {Room} from '../_interfaces/room';
import {Week} from '../_models/week';

@Component({
    selector: 'app-places-form',
    templateUrl: './places-form.component.html',
    styleUrls: ['./places-form.component.css']
})
export class PlacesFormComponent implements OnInit {
    private currentlyEditingSchedule: Schedule;
    private building: Building;
    private rooms: Room[];
    private weekTranslations: {};

    @Input('lecture') lecture: Lecture;
    @Input('buildings') buildings: Building[];
    @Output() lectureEmitter: EventEmitter<Lecture> = new EventEmitter();

    constructor() {}

    ngOnInit() {
        this.weekTranslations = Week.WEEK_TRANSLATION;
        this.rooms = [];
    }

    onBuildingChange(event: MatAutocompleteSelectedEvent) {
        this.setCurrentBuilding(event.option.value);
        this.lecture.schedules.forEach((schedule: Schedule) => {
           if (schedule.weekNumber === this.currentlyEditingSchedule.weekNumber) {
               schedule.building = event.option.value;
           }
        });
        this.lectureEmitter.emit(this.lecture);
    }

    onScheduleEditing(schedule: Schedule) {
        this.currentlyEditingSchedule = schedule;
    }

    onRoomChange(event: MatAutocompleteSelectedEvent) {
        this.lecture.schedules.forEach((schedule: Schedule) => {
            if (schedule.weekNumber === this.currentlyEditingSchedule.weekNumber) {
                schedule.room = event.option.value;
            }
        });

        this.lectureEmitter.emit(this.lecture);
    }

    private setCurrentBuilding(buildingNumber: string) {
        this.building = this.buildings.filter((building: Building) => {
           if (building.name === buildingNumber) {
               return building;
           }
        })[0];

        this.rooms = this.building.rooms;
    }
}
