import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {Week} from '../_models/week';
import {Lecture} from '../_models/lecture';
import {Schedule} from '../_models/schedule';

@Component({
    selector: 'app-semester-weeks',
    templateUrl: './semester-weeks.component.html',
    styleUrls: ['./semester-weeks.component.css']
})
export class SemesterWeeksComponent implements OnInit {
    private distributedHours: number;
    weeksFormGroup: FormGroup;
    weeks: Array<Week> = [
        new Week('1', 0),
        new Week('2', 0),
        new Week('3', 0),
        new Week('4', 0),
        new Week('5', 0),
        new Week('6', 0),
        new Week('7', 0),
        new Week('8', 0),
        new Week('9', 0),
        new Week('10', 0),
        new Week('11', 0),
        new Week('12', 0),
        new Week('13', 0),
        new Week('14', 0),
        new Week('15', 0),
    ];
    formControlNames: string[];

    @Input('lecture') lecture: Lecture;
    @Output() lectureEmitter: EventEmitter<Lecture> = new EventEmitter();

    constructor(
        private formBuilder: FormBuilder,
    ) {
    }

    ngOnInit() {
        this.distributedHours = 0;
        this.initForm();
    }

    getDistributedHours() {
        this.distributedHours = 0;
        Object.keys(this.weeksFormGroup.controls).forEach(function (control) {
            const field = this.weeksFormGroup.get(control);
            this.distributedHours += parseInt(field.value, 10);
        }.bind(this));
    }

    private initForm() {
        this.weeksFormGroup = this.formBuilder.group({});
        this.weeks.forEach(week =>
            this.weeksFormGroup.addControl(week.week, new FormControl(week.hours, [Validators.pattern('^[0-9]*$')]))
        );

        this.initFormControlNames();
        this.initFormValues();
        this.onChanges();
    }

    private onChanges() {
        this.formControlNames.forEach(name => {
            this.weeksFormGroup.get(name).valueChanges.subscribe(value => {
                this.getDistributedHours();
                this.updateSchedules(name, value);
            });
        });
    }

    private initFormControlNames() {
        this.formControlNames = [];
        Object.keys(this.weeksFormGroup.controls).forEach((control: string) => {
            this.formControlNames.push(control);
        });
    }

    private initFormValues() {
        this.lecture.schedules.forEach((schedule: Schedule) => {
            const control = this.weeksFormGroup.get(schedule.weekNumber);
            if (control) {
                control.setValue(schedule.suggestedHours);
                this.distributedHours += schedule.suggestedHours;
            }
        });
    }

    private updateSchedules(name: string, value: number) {
        if (value === 0) {
            this.removeSchedule(name);
        } else {
            this.addOrModifySchedule(name, value);
        }

        this.lectureEmitter.emit(this.lecture);
    }

    private removeSchedule(weekNumber: string) {
        this.lecture.schedules = this.lecture.schedules.filter((schedule: Schedule, index) => {
            return schedule.weekNumber !== weekNumber;
        });
    }

    private addOrModifySchedule(name: string, value: number) {
        const schedule = this.lecture.schedules.filter((schedule: Schedule) => {
            if (schedule.weekNumber === name) {
                schedule.suggestedHours = value;
                return schedule;
            }
        })[0];

        // console.log(schedule);
        if (schedule == null) {
            let schedule = new Schedule(null, name, value, null, null);
            this.lecture.schedules.push(schedule);
        }
    }
}
