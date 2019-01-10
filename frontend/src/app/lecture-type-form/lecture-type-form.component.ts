import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Subscription} from "rxjs";
import {Lecture} from "../_interfaces/lecture";
import {Lecturer} from "../_interfaces/lecturer";
import {Week} from '../_models/week';
import {Building} from "../_interfaces/building";
import {DemandFormService} from "../demand-form/demand-form.service";
import {Schedule} from "../_models/schedule";
import {LectureType} from "../_interfaces/lecture-type";
import {Lecture} from "../_models/lecture";

@Component({
    selector: 'app-lecture-type-form',
    templateUrl: './lecture-type-form.component.html',
    styleUrls: ['./lecture-type-form.component.css']
})
export class LectureTypeFormComponent implements OnInit, OnDestroy {
    registerForm: FormGroup;
    submitted = false;
    fields: Array<string> = [];
    allocatedWeeks: Array<Week>;
    hoursProperlyDistributed: boolean;
    private subscriptions: Subscription;
    private buildings: Building[];

    @Input('schedule') schedule: Schedule;
    @Input('lecture') lecture: Lecture;
    @Input('qualifiedLecturers') qualifiedLecturers: Lecturer[];

    @Output() lectureTypeEmitter: EventEmitter<LectureType>;

    constructor(
        private formBuilder: FormBuilder,
        private demandFormService: DemandFormService
    ) {
    }

    ngOnInit() {
        this.subscriptions = this.demandFormService.getBuildings().subscribe((result: Building[]) => {
            this.buildings = result;
        });

        this.hoursProperlyDistributed = false;
        this.initForm();
    }

    initForm() {
        this.registerForm = this.formBuilder.group({
            lecturer: ['', Validators.required],
            lectureComments: ['', Validators.required],
        });
    }

    ngOnDestroy(): void {
        this.subscriptions.unsubscribe();
    }

    onWeeksAllocation(weeks: Array<Week>) {
        this.allocatedWeeks = weeks;
    }

    onHoursDistribution($event: boolean) {
        this.hoursProperlyDistributed = $event;
    }

    emitLectureType(){
        const lecturer = this.registerForm.get('lecturer').value;
        const lectureComments = this.registerForm.get('lectureComments').value;
        this.lectureTypeEmitter.emit(new Lecture(null, this.qualifiedLecturers[lecturer], this.lecture.hours, this.lecture.comments, , this.lec))
    }
}
