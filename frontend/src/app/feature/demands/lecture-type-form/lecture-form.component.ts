import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {DemandService} from '../demand.service';
import {LectureSet} from '../interfaces/form/lecture-set';
import {Place} from '../interfaces/form/place';
import {Lecturer} from '../interfaces/form/lecturer';
import {Week} from '../../../shared/_models/week';
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
    private weekTranslations = Week.WEEK_TRANSLATION;
    private weeks: {}[] = [];

    constructor(
        private demandService: DemandService
    ) {

    }

    ngOnInit() {
        this.weekTranslations.map(weekTranslation => {
            this.weeks[weekTranslation.number] = {
                label: weekTranslation.label,
                allocatedHours: this.getHoursForGivenWeek(weekTranslation.number),
                room: this.getRoomForGivenWeek(weekTranslation.number),
                building: this.getBuildingForGivenWeek(weekTranslation.number)
            };
        });
    }

    ngOnDestroy(): void {
    }

    onLectureEmitted(lectureSet: LectureSet) {
        this.lectureSet = lectureSet;
        this.lectureEmitter.emit(this.lectureSet);
    }

    getHoursForGivenWeek(number: number) {
        this.lectureSet.allocatedWeeks.map(item => {
            if (item['weekNumber'] === number) {
                return item['allocatedHours'];
            }
        });

        return 0;
    }

    getRoomForGivenWeek(number: number) {
        this.lectureSet.allocatedWeeks.map(item => {
            if (item['weekNumber'] === number) {
                return item['room'];
            }
        });

        return '';
    }

    getBuildingForGivenWeek(number: number) {
        this.lectureSet.allocatedWeeks.map(item => {
            if (item['weekNumber'] === number) {
                return item['building'];
            }
        });

        return '';
    }


    onPlaceChange(place: string) {
        console.log(place);
        // const place = $event.toString().split(' ');
        // this.lectureSet.allocatedWeeks[number]['room'] = place[0];
    }

    onLecturerChange($event) {
    }

    onNotesChange($event: Event) {

    }

    onHoursChange(hours: number, item: any) {
        if (!this.lectureSet.allocatedWeeks[item.number]) {
            this.lectureSet.allocatedWeeks[item.number] = { allocatedHours: hours };
        } else {
            Object.assign(this.lectureSet.allocatedWeeks[item.number], { allocatedHours: hours });
        }
    }

    displayPlace(item: any) {
        return item['building'] + ' ' + item['room'];
    }

    getDistributedHours() {
        this.distributedHours = 0;
        this.lectureSet.allocatedWeeks.map(week => {
            this.distributedHours += week.allocatedHours;
        });
    }
}
