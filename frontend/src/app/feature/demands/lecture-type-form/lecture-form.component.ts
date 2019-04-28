import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {DemandService} from '../demand.service';
import {LectureSet} from '../interfaces/form/lecture-set';
import {Place} from '../interfaces/form/place';
import {Lecturer} from '../interfaces/form/lecturer';
import {Week} from '../../../shared/_models/week';
import {MatAutocompleteSelectedEvent} from "@angular/material";
import {AuthenticationService} from "../../../shared/_services";

@Component({
    selector: 'app-lecture-form',
    templateUrl: './lecture-form.component.html',
    styleUrls: ['./lecture-form.component.css']
})
export class LectureFormComponent implements OnInit, OnDestroy {
    @Input('lectureSet') lectureSet: LectureSet;
    @Input('userRoles') userRoles: [];
    @Input('places') places: Place[];
    @Input('qualifiedLecturers') qualifiedLecturers: Lecturer[];

    @Output() lectureEmitter: EventEmitter<LectureSet> = new EventEmitter();
    private weekTranslations = Week.WEEK_TRANSLATION;
    private weeks: {}[] = [];

    constructor(
        private demandService: DemandService,
        private authenticationService: AuthenticationService
    ) {

    }

    ngOnInit() {
        this.weekTranslations.map(weekTranslation => {
            this.weeks.push({
                number: weekTranslation.number,
                label: weekTranslation.label,
                allocatedHours: this.getHoursForGivenWeek(weekTranslation.number),
                room: this.getRoomForGivenWeek(weekTranslation.number),
                building: this.getBuildingForGivenWeek(weekTranslation.number)
            });
        });
    }

    ngOnDestroy(): void {

    }

    onLectureEmitted() {
        this.lectureEmitter.emit(this.lectureSet);
    }

    getHoursForGivenWeek(number: number) {
        if (this.lectureSet.allocatedWeeks[number]) {
            return this.lectureSet.allocatedWeeks[number].allocatedHours;
        } else {
            return 0;
        }
    }

    getRoomForGivenWeek(number: number) {
        if (this.lectureSet.allocatedWeeks[number]) {
            return this.lectureSet.allocatedWeeks[number].room;
        } else {
            return '';
        }
    }

    getBuildingForGivenWeek(number: number) {
        if (this.lectureSet.allocatedWeeks[number]) {
            return this.lectureSet.allocatedWeeks[number].building;
        } else {
            return '';
        }
    }

    onPlaceChange(place: any, item: any) {
        if (!this.lectureSet.allocatedWeeks[item.number]) {
            this.lectureSet.allocatedWeeks[item.number] = {allocatedHours: 0, room: +place.room, building: +place.building};
        } else {
            Object.assign(this.lectureSet.allocatedWeeks[item.number], {room: +place.room, building: +place.building});
        }

        this.onLectureEmitted();
    }

    onLecturerChange(lecturer: Lecturer) {
        this.lectureSet.lecturer = lecturer;
        this.onLectureEmitted();
    }

    onNotesChange(notes: string) {
        this.lectureSet.notes = notes;
        this.onLectureEmitted();
    }

    onHoursChange(hours: number, item: any) {
        console.log(item);
        if (!this.lectureSet.allocatedWeeks[item.number]) {
            this.lectureSet.allocatedWeeks[item.number] = {allocatedHours: hours, room: null, building: null};
        } else {
            Object.assign(this.lectureSet.allocatedWeeks[item.number], {allocatedHours: hours});
        }
        this.onLectureEmitted();
    }

    displayPlace(item: any) {
        return item['building'] + ' ' + item['room'];
    }

    displayLecturer(item: any) {
        return item.username;
    }

    getDistributedHours() {
        let distributedHours = 0;
        for (let key in this.lectureSet.allocatedWeeks) {
            distributedHours += +this.lectureSet.allocatedWeeks[key].allocatedHours;
        }

        return distributedHours;
    }
}
