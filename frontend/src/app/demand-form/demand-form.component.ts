import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {DemandFormService} from './demand-form.service';
import {Observable, Subscription} from 'rxjs';
import {DemandElement} from '../demand-list/demand-element';
import {Lecture} from '../_models/lecture';

@Component({
    selector: 'app-demand-form',
    templateUrl: './demand-form.component.html',
    styleUrls: ['./demand-form.component.css']
})
export class DemandFormComponent implements OnInit, OnDestroy {
    submitted = false;
    demandElement: DemandElement;
    private subscriptions: Subscription;
    private qualifiedLecturers: Object;

    private lectures: Lecture[];

    constructor(
        private route: ActivatedRoute,
        private demandFormService: DemandFormService
    ) {
    }

    ngOnInit() {
        this.lectures = [];
        const id = this.route.snapshot.paramMap.get('id');
        const demandSubscription = this.getDemandDetails(id).subscribe(res => {
            this.demandElement = res;
        });
        this.subscriptions = this.getQualifiedLecturers(id).subscribe(res => {
            this.qualifiedLecturers = res;
        });

        this.subscriptions.add(demandSubscription);
    }

    onSubmit() {
        this.submitted = true;
    }

    private getDemandDetails(id: string): Observable<DemandElement> {
        return this.demandFormService.getDemandDetails(id);
    }

    ngOnDestroy(): void {
        this.subscriptions.unsubscribe();
    }

    private getQualifiedLecturers(id) {
        return this.demandFormService.getLecturers(id);
    }

    onEmittedLecture(lecture: Lecture) {
        if (this.lectureExists(lecture.lectureType.id)) {
            this.removeLectureFromArray(lecture.lectureType.id);
        }

        this.lectures.push(lecture);
    }


    lectureExists(id: number): boolean {
        return this.lectures.some((lecture: Lecture) => lecture.lectureType.id === id);
    }

    removeLectureFromArray(id: number) {
        this.lectures.forEach( (lecture: Lecture, index) => {
            if (lecture.lectureType.id === id) { this.lectures.splice(index, 1); }
        });
    }
}
