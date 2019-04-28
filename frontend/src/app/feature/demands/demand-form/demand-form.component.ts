import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {DemandService} from "../demand.service";
import {Subscription} from "rxjs";
import {Lecture} from "../../../shared/_interfaces/lecture";
import {Demand, DemandStatus} from "../interfaces/form/demand";
import {Lecturer} from "../interfaces/form/lecturer";
import {Place} from "../interfaces/form/place";
import {FlashMessagesService} from "angular2-flash-messages";
import * as FileSaver from "file-saver";
import {AuthenticationService} from "../../../shared/_services";

@Component({
  selector: 'app-demand-form',
  templateUrl: './demand-form.component.html',
  styleUrls: ['./demand-form.component.css']
})
export class DemandFormComponent implements OnInit, OnDestroy {
  demand: Demand;
  private subscriptions: Subscription;
  private qualifiedLecturers: Lecturer[];
  private places: Place[];
  private lectures: Lecture[];

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private demandService: DemandService,
    private flashMessageService: FlashMessagesService,
    private authenticationService: AuthenticationService
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

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
  }

  onEmittedLecture(lecture: Lecture) {
    this.lectures.push(lecture);
  }

  onSend() {
    this.demandService.updateDemand(this.demand).subscribe(() => {
      this.demandService.acceptDemand(this.demand.uuid).subscribe(() => {
        this.flashMessageService.show('Zapotrzebowanie zostało zaktualizowane.');
        this.router.navigate(['/zapotrzebowania']);
      }, error1 => {
        this.flashMessageService.show(error1.error.error.exception[0].message);
      });
    },error1 => {
      this.flashMessageService.show(error1.error.error.exception[0].message);
    });
  }

  onSave() {
    this.demandService.updateDemand(this.demand).subscribe(() => {
      this.flashMessageService.show('Zapotrzebowanie zostało zapisane');
      this.router.navigate(['/zapotrzebowania']);
    }, error1 => {
      this.flashMessageService.show(error1.error.error.exception[0].message);
    });
  }

  onCancel() {
    this.demandService.declineDemand(this.demand.uuid).subscribe(() => {
      this.flashMessageService.show('Zapotrzebowanie odrzucono');
      this.router.navigate(['/zapotrzebowania']);

    }, error1 => {
      this.flashMessageService.show(error1.error.error.exception[0].message);
    });
  }

  downloadPdf() {
    this.demandService.downloadDemand(this.demand.uuid).subscribe(res => {
      var file = new Blob(["\ufeff", res], {type: 'application/pdf'});
      FileSaver.saveAs(file, this.demand.groupName + '/' + this.demand.subjectName + '/' + this.demand.semester, true);
    });
  }

  onAssigment() {
      this.demandService.assignDemand(this.demand).subscribe((res) => {
        this.flashMessageService.show('Przypisano zapotrzebowanie');
        this.router.navigate(['/zapotrzebowania']);
      });
  }

  isDemandAcceptedByLecturer() {
    return this.demand.status === DemandStatus.STATUS_ACCEPTED_BY_TEACHER;
  }
}
