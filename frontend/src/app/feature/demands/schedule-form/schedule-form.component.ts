import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Lecture} from '../../../shared/_models/lecture';
import {Building} from '../../../shared/_interfaces/building';

@Component({
  selector: 'app-schedule-form',
  templateUrl: './schedule-form.component.html',
  styleUrls: ['./schedule-form.component.css']
})
export class ScheduleFormComponent implements OnInit {
  hoursProperlyDistributed: boolean;

  @Input('lecture') lecture: Lecture;
  @Input('buildings') buildings: Building[];
  @Output() lectureEmitter: EventEmitter<Lecture> = new EventEmitter();

  constructor() {}

  ngOnInit() {
    this.hoursProperlyDistributed = false;
  }

  onLectureUpdate(lecture: Lecture) {
    this.lecture = lecture;
    this.lectureEmitter.emit(lecture);
  }
}
