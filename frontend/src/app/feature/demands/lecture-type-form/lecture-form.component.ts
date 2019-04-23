import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {DemandService} from "../demand.service";
import {LectureSet} from "../interfaces/form/lecture-set";
import {Place} from "../interfaces/form/place";
import {Lecturer} from "../interfaces/form/lecturer";
import {Week} from "../../../shared/_models/week";
import {MatAutocompleteSelectedEvent} from "@angular/material";

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
    private distributedHours: number;
    private weeks = Week.WEEK_TRANSLATION;
    constructor(
        private demandService: DemandService
    ) {

    }

    ngOnInit() {

    }

    ngOnDestroy(): void {
    }

    onLectureEmitted(lectureSet: LectureSet) {
        this.lectureSet = lectureSet;
        this.lectureEmitter.emit(this.lectureSet);
    }

    getHoursForGivenWeek(number: number) {
        this.lectureSet.allocatedWeeks.map(item => {
            if(item['weekNumber'] === number) {
                return item['allocatedHours'];
            }
        });

        return 0;
    }

    getPlaceForGivenWeek(number: number) {
        this.lectureSet.allocatedWeeks.map(item => {
            if(item['weekNumber'] === number) {
                return item['building'] + ' ' + item['room'];
            }
        });

        return '';
    }

    onPlaceChange($event: MatAutocompleteSelectedEvent) {
        let place = $event.toString().split(' ');
        this.lectureSet.allocatedWeeks[number]['room'] = place[0];
        console.log('onPlaceChange');
    }

    onLecturerChange($event) {
        console.log('onLecturerChange');

    }

    onNotesChange($event: Event) {
        console.log('onNotesChange');
    }

    onHoursChange($event: Event, number: number) {
        console.log($event);
        console.log('onHoursChange');
        // this.lectureSet.allocatedWeeks[number]['allocatedHours'] = $event
    }
}
