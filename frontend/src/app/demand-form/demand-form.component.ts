import {AfterViewInit, Component, ElementRef, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {DemandFormService} from './demand-form.service';
import {Observable, Subscription} from 'rxjs';
import {DemandElement} from '../demand-list/demand-element';
import {Lecture} from '../_models/lecture';
import {Building} from '../_interfaces/building';
import * as JSPdf from 'jspdf';

import html2canvas from 'html2canvas';
@Component({
    selector: 'app-demand-form',
    templateUrl: './demand-form.component.html',
    styleUrls: ['./demand-form.component.css']
})
export class DemandFormComponent implements OnInit, OnDestroy {
  @ViewChild('demandContent') demandContent: ElementRef;
  submitted = false;
  demandElement: DemandElement;
  private subscriptions: Subscription;
  private qualifiedLecturers: Object;
  private buildings: Building[];
  private lectures: Lecture[];
  private currentlyLoggedUserRoles: Object;

    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private demandFormService: DemandFormService,
    ) {
    }

    ngOnInit() {
        const id = this.route.snapshot.paramMap.get('id');
        this.lectures = [];

        this.subscriptions = new Subscription();
        this.subscriptions.add(this.getDemandDetails(id).subscribe(res => {
            this.demandElement = res;
        }));

        this.subscriptions.add(this.getQualifiedLecturers(id).subscribe(res => {
            this.qualifiedLecturers = res;
        }));

        this.subscriptions.add(this.demandFormService.getBuildings().subscribe(res => {
            this.buildings = res;
        }));
        console.log(localStorage.getItem('currentUser'));
        this.subscriptions.add(this.demandFormService.getRoles().subscribe(roles => {
            this.demandFormService.roles = roles;
        }));
    }

    onSubmit() {
        if (this.lectures.length > 0) {
            this.demandElement.lectures = this.lectures;
        }

        this.demandFormService.updateDemand(this.demandElement);
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

  showPdf() {
    var printWindow = window.open('', '', 'height=400,width=800');
    printWindow.document.write('<html><head><title>DIV Contents</title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(this.demandContent.toString());
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }

    onCancel() {
        this.demandFormService.cancelDemand(this.demandElement);
    }
}
