import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {DemandService} from "../demand.service";
import {Subscription} from "rxjs";
import {Lecture} from "../../../shared/_interfaces/lecture";
import {Demand} from "../interfaces/form/demand";
import {Lecturer} from "../interfaces/form/lecturer";
import {Place} from "../interfaces/form/place";

@Component({
  selector: 'app-demand-form',
  templateUrl: './demand-form.component.html',
  styleUrls: ['./demand-form.component.css']
})
export class DemandFormComponent implements OnInit, OnDestroy {
  // @ViewChild('demandContent') demandContent: ElementRef;
  // submitted = false;
  demand: Demand;
  private subscriptions: Subscription;
  private qualifiedLecturers: Lecturer[];
  private places: Place[];
  private lectures: Lecture[];

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private demandService: DemandService,
  ) {
  }

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    this.lectures = [];

    this.subscriptions = new Subscription();
    this.subscriptions.add(this.demandService.getDemandDetails(id).subscribe(res => {
      this.demand = res;
      this.subscriptions.add(this.demandService.getQualifiedLecturers(this.demand.subjectName).subscribe(res => {
        this.qualifiedLecturers = res;
      }));
    }));

    this.subscriptions.add(this.demandService.getPlaces().subscribe(res => {
        this.places = res;
    }));
  }

  onSubmit() {
    // if (this.lectures.length > 0) {
    //   this.demand.lectureSets = this.lectures;
    // }
    //
    // this.demandService.updateDemand(this.demand);
  }

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
  }

  onEmittedLecture(lecture: Lecture) {
    // if (this.lectureExists(lecture.lectureType.id)) {
    //   this.removeLectureFromArray(lecture.lectureType.id);
    // }

    this.lectures.push(lecture);
  }

  // onCancel() {
  //   this.demandService.cancelDemand(this.demand);
  // }
  onSend() {

  }

  onSave() {

  }

  onCancel() {

  }
}
