import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AbstractControl, FormBuilder, FormGroup, Validators} from "@angular/forms";
import {stringify} from "querystring";
import {Week} from '../_models/week';
import {Schedule} from "../_models/schedule";

@Component({
    selector: 'app-semester-weeks',
    templateUrl: './semester-weeks.component.html',
    styleUrls: ['./semester-weeks.component.css']
})
export class SemesterWeeksComponent implements OnInit {
    private distributedHours: number;
    allocatedWeeks: Array<Week>;

    @Input('totalHours') totalHours: number;
    @Output() allocatedWeeksOutput = new EventEmitter<Array<Week>>();
    @Output() hoursProperlyDistributed = new EventEmitter<boolean>();

    constructor(
        private formBuilder: FormBuilder,
    ) {
    }

    weeksFormGroup: FormGroup;

    ngOnInit() {
        this.distributedHours = 0;
        this.initForm();
    }

    getDistributedHours() {
        this.distributedHours = 0;
        Object.keys(this.weeksFormGroup.controls).forEach(function (control) {
            let field = this.weeksFormGroup.get(control);
            this.distributedHours += field.value;
        }.bind(this));
    }

    private initForm() {
        this.weeksFormGroup = this.formBuilder.group({
            week1: [0, [Validators.pattern("^[0-9]*$")]],
            week2: [0, [Validators.pattern("^[0-9]*$")]],
            week3: [0, [Validators.pattern("^[0-9]*$")]],
            week4: [0, [Validators.pattern("^[0-9]*$")]],
            week5: [0, [Validators.pattern("^[0-9]*$")]],
            week6: [0, [Validators.pattern("^[0-9]*$")]],
            week7: [0, [Validators.pattern("^[0-9]*$")]],
            week8: [0, [Validators.pattern("^[0-9]*$")]],
            week9: [0, [Validators.pattern("^[0-9]*$")]],
            week10: [0, [Validators.pattern("^[0-9]*$")]],
            week11: [0, [Validators.pattern("^[0-9]*$")]],
            week12: [0, [Validators.pattern("^[0-9]*$")]],
            week13: [0, [Validators.pattern("^[0-9]*$")]],
            week14: [0, [Validators.pattern("^[0-9]*$")]],
            week15: [0, [Validators.pattern("^[0-9]*$")]]
        });

        this.onChanges();
    }

    private onChanges() {
        this.weeksFormGroup.valueChanges.subscribe(val => {
            this.updateDistributedHoursAndCheckFormValidity();
        });
    }

    private updateDistributedHoursAndCheckFormValidity() {
        this.getDistributedHours();
        this.allocateWeeks();
    }

    private checkIfDistributedHoursExceededTotalHours(): boolean {
        console.log(this.totalHours);
        console.log(this.distributedHours);
        if (this.totalHours >= this.distributedHours) {
            return true;
        }

        return false;
    }

    private allocateWeek(field: AbstractControl) {
        if (field.value > 0) {
            this.allocatedWeeks.push(new Week(this.getFormControlName(field), field.value));
        } else {
            this.allocatedWeeks.slice(this.allocatedWeeks.indexOf(stringify(field), 1));
        }
    }

    private getFormControlName(control: AbstractControl): string | null {
        let group = <FormGroup>control.parent;

        if (!group) {
            return null;
        }

        let name: string;

        Object.keys(group.controls).forEach(key => {
            let childControl = group.get(key);

            if (childControl !== control) {
                return;
            }

            name = key;
        });

        return name;
    }

    private allocateWeeks() {
        this.allocatedWeeks = [];
        Object.keys(this.weeksFormGroup.controls).forEach(function (control) {
            let field = this.weeksFormGroup.get(control);
            this.allocateWeek(field);
        }.bind(this));
        this.allocatedWeeksOutput.emit(this.allocatedWeeks);
        this.hoursProperlyDistributed.emit(this.checkIfDistributedHoursExceededTotalHours());
    }
}
