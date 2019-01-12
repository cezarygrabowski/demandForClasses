import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Subscription} from 'rxjs';
import {Lecturer} from '../_interfaces/lecturer';
import {Schedule} from '../_models/schedule';
import {Lecture} from '../_models/lecture';

@Component({
    selector: 'app-lecture-form',
    templateUrl: './lecture-form.component.html',
    styleUrls: ['./lecture-form.component.css']
})
export class LectureFormComponent implements OnInit, OnDestroy {
    submitted = false;
    fields: Array<string> = [];

    private subscriptions: Subscription;

    private schedules: Array<Schedule>;
    private lectureSaved = false;
    @Input('lecture') lecture: Lecture;
    @Input('qualifiedLecturers') qualifiedLecturers: Lecturer[];

    @Output() lectureEmitter: EventEmitter<Lecture> = new EventEmitter();
    lectureForm: FormGroup;

    constructor(
        private formBuilder: FormBuilder,
    ) {
    }

    ngOnInit() {
        this.initForm();
        this.schedules = this.lecture.schedules;
        this.saveLecture();
    }

    initForm() {
        this.lectureForm = this.formBuilder.group({
            lecturer: [this.lecture.lecturer.username, Validators.required],
            lectureComments: [this.lecture.comments, Validators.required],
        });

        this.onChanges();
    }

    ngOnDestroy(): void {
        this.subscriptions.unsubscribe();
    }

    saveLecture() {
        this.lectureSaved = true;
        this.lecture.lecturer = this.lectureForm.get('lecturer').value;
        this.lecture.comments = this.lectureForm.get('lectureComments').value;
        this.lecture.schedules = this.schedules;
        this.lectureEmitter.emit(this.lecture);
    }

    private onLectureChange() {
        this.lectureSaved = false;
    }

    private onChanges() {
        this.lectureForm.valueChanges.subscribe(() =>
            this.onLectureChange()
        );
    }

    onSchedulesChange(schedules: Schedule[]) {
        this.schedules = schedules;
        this.onLectureChange();
    }

    onSemesterWeeksChanged() {
        this.onLectureChange();
    }
}
