import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Subscription} from 'rxjs';
import {Lecturer} from '../_interfaces/lecturer';
import {Lecture} from '../_models/lecture';
import {Building} from '../_interfaces/building';

@Component({
    selector: 'app-lecture-form',
    templateUrl: './lecture-form.component.html',
    styleUrls: ['./lecture-form.component.css']
})
export class LectureFormComponent implements OnInit, OnDestroy {

    lectureForm: FormGroup;

    @Input('lecture') lecture: Lecture;
    @Input('buildings') buildings: Building[];
    @Input('qualifiedLecturers') qualifiedLecturers: Lecturer[];

    @Output() lectureEmitter: EventEmitter<Lecture> = new EventEmitter();

    constructor(
        private formBuilder: FormBuilder,
    ) {}

    ngOnInit() {
        this.initForm();
    }

    initForm() {
        this.lectureForm = this.formBuilder.group({
            lecturer: [this.lecture.lecturer.username, Validators.required],
            lectureComments: [this.lecture.comments, Validators.required],
        });

        this.onChanges();
    }

    ngOnDestroy(): void {
    }

    private onLectureChange() {
        this.lecture.lecturer = this.lectureForm.get('lecturer').value;
        this.lecture.comments = this.lectureForm.get('lectureComments').value;
        this.lectureEmitter.emit(this.lecture);
    }

    private onChanges() {
        this.lectureForm.valueChanges.subscribe(() =>
            this.onLectureChange()
        );
    }

    onLectureEmitted(lecture: Lecture) {
        this.lecture = lecture;
        this.lectureEmitter.emit(lecture);
    }
}
