import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Week} from "../_models/week";
import {Lecture} from "../_models/lecture";
import {Schedule} from "../_models/schedule";
import {Building} from "../_interfaces/building";
import {Subscription} from "rxjs";
import {DemandFormService} from "../demand-form/demand-form.service";

@Component({
  selector: 'app-schedule-form',
  templateUrl: './schedule-form.component.html',
  styleUrls: ['./schedule-form.component.css']
})
export class ScheduleFormComponent implements OnInit {
  allocatedWeeks: Array<Week>;
  hoursProperlyDistributed: boolean;
  schedules: Array<Schedule> = [];
  private subscriptions: Subscription;
  private buildings: Building[];

  @Input('lecture') lecture: Lecture;

  @Output() semesterWeeksChangedEmitter: EventEmitter<boolean> = new EventEmitter();
  @Output() schedulesEmitter: EventEmitter<Schedule[]> = new EventEmitter();

  constructor(private demandFormService: DemandFormService) {}

  ngOnInit() {
    this.subscriptions = this.demandFormService.getBuildings().subscribe((result: Building[]) => {
      this.buildings = result;
    });

    this.hoursProperlyDistributed = false;
    this.schedulesEmitter.emit(null);
  }

  onWeeksAllocation(weeks: Array<Week>) {
    this.allocatedWeeks = weeks;
    this.semesterWeeksChanged();
  }

  onHoursDistribution($event: boolean) {
    this.hoursProperlyDistributed = $event;
    this.semesterWeeksChanged();
  }

  onScheduleChanged(schedule: Schedule) {
    if (this.scheduleExists(schedule.weekNumber)) {
      this.removeScheduleFromArray(schedule.weekNumber);
    }

    this.schedules.push(schedule);
    this.schedulesEmitter.emit(this.schedules);
  }

  scheduleExists(weekNumber: string): boolean {
    return this.schedules.some((schedule: Schedule) => schedule.weekNumber === weekNumber);
  }

  removeScheduleFromArray(weekNumber: string) {
    this.schedules.forEach( (schedule: Schedule, index) => {
      if (weekNumber === schedule.weekNumber) { this.schedules.splice(index, 1); }
    });
  }

  semesterWeeksChanged() {
    this.semesterWeeksChangedEmitter.emit();
  }
}
